<?php

// 1. Setup Environment
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/threshold_alert_log.log');

// Start Log
error_log("========== SLA SCRIPT STARTED: " . date("Y-m-d H:i:s") . " ==========");

// 2. Dependencies
include('config_approval.php');
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 3. Database Check & Timezone
date_default_timezone_set('Asia/Kolkata');
if (!$dbcon || $dbcon->connect_error) {
    error_log("CRITICAL: Database connection failed: " . ($dbcon->connect_error ?? 'Unknown'));
    exit;
}
$dbcon->query("SET time_zone = '+05:30'");

// 4. Fetch SMTP Config (Once)
$config_query = $dbcon->query("SELECT * FROM itesm_sender_email LIMIT 1");
$smtpConfig = $config_query->fetch_assoc();

if (!$smtpConfig) {
    error_log("CRITICAL: Email config missing");
    exit;
}

// 5. Helper Functions

/**
 * Optimized Mailer Setup
 */
function getMailerInstance($config) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = (bool)$config['auth'];
    $mail->Username   = $config['email_id'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['secure'] ?: PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $config['port'];
    $mail->SMTPKeepAlive = true; // Optimization: Keep connection open
    $mail->setFrom($config['email_id'], 'IT Helpdesk');
    $mail->isHTML(true);
    return $mail;
}

/**
 * Generic Fetch Recipients Function
 */
function getRecipients($dbcon, $type) {
    $emails = [];
    
    // Site Based (Common for both)
    // Note: Consider removing hardcoded '2' region_id or moving to config
    $siteStmt = $dbcon->query("SELECT DISTINCT forward_to FROM itesm_org_site WHERE region_id IN (2) AND forward_to != ''");
    while ($row = $siteStmt->fetch_assoc()) {
        $emails['site'][] = trim($row['forward_to']);
    }

    // Specific SLA Type Based
    $col = ($type === 'response') ? 'response_sla_mail' : 'resolution_sla_mail';
    $userStmt = $dbcon->query("SELECT primary_email FROM itesm_users WHERE $col = 1 AND is_active = 1 AND primary_email != ''");
    while ($row = $userStmt->fetch_assoc()) {
        $emails['specific'][] = trim($row['primary_email']);
    }
    
    return $emails;
}

// Initialize Mailer Object
$mail = getMailerInstance($smtpConfig);

// ---------------------------------------------------------
// PROCESS 1: RESPONSE SLA BREACHES
// ---------------------------------------------------------

$recipientsResponse = getRecipients($dbcon, 'response');

$response_sql = "
SELECT * FROM (
    SELECT 
        r.id, r.ticket_no, r.username, r.subject, r.contact_no, r.description,
        loc.city, s.status_name, sub.response_time,
        MIN(CASE WHEN a.status_id = 1 THEN a.date_time END) AS open_time,
        MIN(CASE WHEN a.status_id = 2 THEN a.date_time END) AS assigned_time
    FROM itesm_request_management r
    JOIN itesm_request_audit a ON a.request_id = r.id
    JOIN itesm_helpdesk_subcategory sub ON sub.id = r.sub_category_id
    LEFT JOIN itesm_org_site loc ON r.location_id = loc.id
    LEFT JOIN itesm_helpdesk_status s ON r.status_id = s.id
    WHERE r.response_time_notified = 0 AND r.status_id IN (1)
    GROUP BY r.id
) t
WHERE (
    (t.assigned_time IS NULL AND NOW() > DATE_ADD(t.open_time, INTERVAL t.response_time MINUTE))
    OR 
    (t.assigned_time IS NOT NULL AND t.assigned_time > DATE_ADD(t.open_time, INTERVAL t.response_time MINUTE))
)
ORDER BY t.open_time ASC LIMIT 50"; // INCREASED LIMIT

$resResult = $dbcon->query($response_sql);

if ($resResult->num_rows > 0) {
    while ($row = $resResult->fetch_assoc()) {
        try {
            $mail->clearAddresses();
            $mail->clearCCs();
            $mail->clearAttachments();

            // Add Recipients
            foreach (($recipientsResponse['site'] ?? []) as $em) $mail->addAddress($em);
            foreach (($recipientsResponse['specific'] ?? []) as $em) $mail->addCC($em);

            $mail->Subject = "Response SLA Breach | Ticket {$row['ticket_no']}";
            
            // Build Body (Simplified for brevity)
            $openTime = date("d-m-Y H:i:s", strtotime($row['open_time']));
            $assignedTime = $row['assigned_time'] ? date("d-m-Y H:i:s", strtotime($row['assigned_time'])) : '<span style="color:red;">Not Assigned</span>';
            $desc = nl2br(strip_tags(htmlspecialchars_decode($row['description'])));

            $mail->Body = "
                <p>Dear Team,</p>
                <p>Ticket <b>{$row['ticket_no']}</b> has breached Response SLA.</p>
                <ul>
                    <li>Subject: {$row['subject']}</li>
                    <li>Requester: {$row['username']}</li>
                    <li>Created: $openTime</li>
                    <li>Assigned: $assignedTime</li>
                </ul>
                <p>Description:<br>$desc</p>
                <p>Regards, IT Helpdesk</p>
            ";

            if ($mail->send()) {
                $dbcon->query("UPDATE itesm_request_management SET response_time_notified = 1 WHERE id = " . $row['id']);
                echo "Response Breach Sent: {$row['ticket_no']}\n";
            }
        } catch (Exception $e) {
            error_log("Response Mail Error [{$row['ticket_no']}]: " . $mail->ErrorInfo);
            // Reset connection if it failed
            $mail->getSMTPInstance()->reset();
        }
    }
}

// ---------------------------------------------------------
// PROCESS 2: RESOLUTION SLA BREACHES
// ---------------------------------------------------------

$recipientsResolution = getRecipients($dbcon, 'resolution');

$resolution_sql = "
SELECT * FROM (
    SELECT 
        r.id, r.ticket_no, r.subject, r.description, r.date_time,
        u.first_name, u.last_name, u.primary_email AS tech_email,
        loc.city, s.status_name, sub.resolution_time,
        MIN(CASE WHEN a.status_id = 2 THEN a.date_time END) AS assigned_time
    FROM itesm_request_management r
    JOIN itesm_request_audit a ON a.request_id = r.id
    JOIN itesm_helpdesk_subcategory sub ON sub.id = r.sub_category_id
    JOIN itesm_users u ON r.technician_id = u.id
    LEFT JOIN itesm_org_site loc ON r.location_id = loc.id
    LEFT JOIN itesm_helpdesk_status s ON r.status_id = s.id
    WHERE r.resolution_notified = 0 AND r.status_id IN (2,3,4)
    GROUP BY r.id
) t
WHERE t.assigned_time IS NOT NULL 
AND NOW() > DATE_ADD(t.assigned_time, INTERVAL t.resolution_time MINUTE)
ORDER BY t.assigned_time ASC LIMIT 50"; // INCREASED LIMIT

$resSlaResult = $dbcon->query($resolution_sql);

if ($resSlaResult->num_rows > 0) {
    while ($row = $resSlaResult->fetch_assoc()) {
        try {
            $mail->clearAddresses();
            $mail->clearCCs();

            // Add Tech Email
            if (!empty($row['tech_email'])) $mail->addAddress($row['tech_email']);
            
            // Add Site Emails & SLA Managers
            foreach (($recipientsResolution['site'] ?? []) as $em) $mail->addAddress($em);
            foreach (($recipientsResolution['specific'] ?? []) as $em) $mail->addCC($em);

            $mail->Subject = "Resolution SLA Breach | Ticket {$row['ticket_no']}";
            
            $assigned = date("d-m-Y H:i:s", strtotime($row['assigned_time']));
            $desc = nl2br(strip_tags(htmlspecialchars_decode($row['description'])));

            $mail->Body = "
                <p>Dear {$row['first_name']},</p>
                <p>Ticket <b>{$row['ticket_no']}</b> has breached Resolution SLA.</p>
                <ul>
                    <li>Subject: {$row['subject']}</li>
                    <li>Assigned Time: {$assigned}</li>
                    <li>Allowed Time: {$row['resolution_time']} Min</li>
                </ul>
                <p>Description:<br>$desc</p>
                <p>Regards, IT Helpdesk</p>
            ";

            if ($mail->send()) {
                $dbcon->query("UPDATE itesm_request_management SET resolution_notified = 1 WHERE id = " . $row['id']);
                echo "Resolution Breach Sent: {$row['ticket_no']}\n";
            }
        } catch (Exception $e) {
            error_log("Resolution Mail Error [{$row['ticket_no']}]: " . $mail->ErrorInfo);
            $mail->getSMTPInstance()->reset();
        }
    }
}

// Close Connection
$mail->smtpClose();
$dbcon->close();
error_log("========== SLA SCRIPT FINISHED ==========");
?>