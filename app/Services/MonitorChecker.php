<?php

namespace App\Services;

use App\Models\Monitor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Exception;

class MonitorChecker
{
    /**
     * Execute the check for the given monitor.
     * Returns an array with keys: latency (float), status (string), packet_loss (int|nullable).
     */
    public function check(Monitor $monitor): array
    {
        return match ($monitor->type) {
            'http', 'keyword' => $this->checkHttp($monitor),
            'port' => $this->checkPort($monitor),
            default => $this->checkPing($monitor),
        };
    }

    protected function checkPing(Monitor $monitor): array
    {
        // Use existing Pinger logic, but inline or via dependency if preferred.
        // For simplicity and completeness, reusing the Pinger structure here or delegating.
        // Let's delegate to a simple ping command wrapper.
        
        $ip = $monitor->ip_address;
        // Linux: ping -c 3 -W 1 $ip
        // Windows: ping -n 3 -w 1000 $ip
        
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cmd = $isWindows 
            ? "ping -n 3 -w 1000 {$ip}"
            : "ping -c 3 -W 1 {$ip}";

        $result = Process::run($cmd);
        $output = $result->output();
        
        $latency = 0;
        $packetLoss = 100;
        
        if ($isWindows) {
            if (preg_match('/Average = (\d+)ms/', $output, $matches)) {
                $latency = (float)$matches[1];
            }
            if (preg_match('/Lost = (\d+) \((\d+)% loss\)/', $output, $matches)) {
                $packetLoss = (int)$matches[2];
            }
        } else {
             if (preg_match('/rtt min\/avg\/max\/mdev = [\d\.]+\/([\d\.]+)\//', $output, $matches)) {
                $latency = (float) $matches[1];
            }
            if (preg_match('/(\d+)% packet loss/', $output, $matches)) {
                $packetLoss = (int) $matches[1];
            }
        }
        
        $status = $packetLoss >= 100 ? 'offline' : 'online';
        // Check for warning (high latency)
        if ($status === 'online' && $latency > 200) $status = 'warning';

        return [
            'latency' => $latency,
            'packet_loss' => $packetLoss,
            'status' => $status,
        ];
    }

    protected function checkHttp(Monitor $monitor): array
    {
        $startTime = microtime(true);
        
        try {
            $method = $monitor->method ?? 'GET';
            $timeout = $monitor->request_timeout ?? 30;
            $verify = $monitor->verify_ssl ?? true;
            $redirects = $monitor->follow_redirects ?? true;
            
            $client = Http::timeout($timeout);
            
            if (!$verify) $client->withoutVerifying();
            if (!$redirects) $client->withoutRedirecting();
            if ($monitor->authentication_user) {
                $client->withBasicAuth($monitor->authentication_user, $monitor->authentication_password);
            }

            $response = $client->send($method, $monitor->ip_address); // ip_address holds URL for HTTP type
            
            $latency = (microtime(true) - $startTime) * 1000; // ms
            $statusCode = $response->status();
            
            // Check accepted status codes
            $accepted = $monitor->accepted_status_codes ?? '200-299';
            $isAccepted = $this->checkStatusCode($statusCode, $accepted);
            
            $status = $isAccepted ? 'online' : 'offline';
            
            // Keyword check
            if ($status === 'online' && $monitor->type === 'keyword' && $monitor->keyword) {
                $body = $response->body();
                $exists = str_contains($body, $monitor->keyword);
                $shouldExist = $monitor->keyword_should_exist ?? true;
                
                if ($shouldExist && !$exists) $status = 'offline'; // Keyword missing
                /*
                 * Logic clarification:
                 * If "Alert IF Keyword Exists" (shouldExist = false), then finding it means BAD (offline).
                 * If "Alert IF Keyword Missing" (shouldExist = true), then NOT finding it means BAD (offline).
                 */
                if (!$shouldExist && $exists) $status = 'offline'; // Keyword present when it shouldn't be
            }
            
            return [
                'latency' => $latency,
                'packet_loss' => 0,
                'status' => $status,
                'response_code' => $statusCode,
            ];

        } catch (Exception $e) {
            return [
                'latency' => 0,
                'packet_loss' => 100,
                'status' => 'offline',
                'error' => $e->getMessage()
            ];
        }
    }

    protected function checkPort(Monitor $monitor): array
    {
        $startTime = microtime(true);
        $connected = false;
        
        try {
            $fp = @fsockopen($monitor->ip_address, $monitor->port ?? 80, $errno, $errstr, $monitor->request_timeout ?? 10);
            if ($fp) {
                $connected = true;
                fclose($fp);
            }
        } catch (Exception $e) {
            $connected = false;
        }

        $latency = (microtime(true) - $startTime) * 1000;

        return [
            'latency' => $connected ? $latency : 0,
            'packet_loss' => $connected ? 0 : 100,
            'status' => $connected ? 'online' : 'offline',
        ];
    }
    
    protected function checkStatusCode(int $code, string $rangeStr): bool
    {
        // Parse range "200-299" or "200, 201"
        $parts = explode(',', $rangeStr);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_contains($part, '-')) {
                [$min, $max] = explode('-', $part);
                if ($code >= $min && $code <= $max) return true;
            } else {
                if ($code == $part) return true;
            }
        }
        return false;
    }
}
