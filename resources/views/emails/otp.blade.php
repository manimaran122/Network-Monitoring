<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body { margin: 0; padding: 0; background: #0f172a; font-family: 'Inter', Arial, sans-serif; color: #e2e8f0; }
        .wrapper { max-width: 520px; margin: 40px auto; background: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); padding: 36px 40px; text-align: center; border-bottom: 1px solid #334155; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.5px; }
        .header p { margin: 6px 0 0; font-size: 13px; color: #94a3b8; }
        .body { padding: 36px 40px; }
        .body p { font-size: 14px; line-height: 1.7; color: #cbd5e1; margin: 0 0 16px; }
        .otp-box { background: #0f172a; border: 2px solid #10b981; border-radius: 12px; padding: 24px; text-align: center; margin: 24px 0; }
        .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #10b981; font-family: 'Courier New', monospace; }
        .otp-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 2px; margin-top: 8px; }
        .expiry { display: flex; align-items: center; gap: 8px; background: #422006; border: 1px solid #78350f; border-radius: 8px; padding: 12px 16px; margin: 20px 0; font-size: 13px; color: #fbbf24; }
        .footer { padding: 20px 40px; border-top: 1px solid #334155; text-align: center; }
        .footer p { font-size: 11px; color: #475569; margin: 0; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🔐 Monitoring Tool</h1>
            <p>Password Reset Request</p>
        </div>
        <div class="body">
            <p>Hi <strong style="color:#f1f5f9">{{ $userName }}</strong>,</p>
            <p>We received a request to reset your password. Use the OTP below to verify your identity and set a new password.</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-label">One-Time Password</div>
            </div>

            <div class="expiry">
                ⚠️ This OTP expires in <strong>{{ $expiresInMinutes }} minutes</strong>. Do not share it with anyone.
            </div>

            <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
        </div>
        <div class="footer">
            <p>This is an automated message from <strong>Monitoring Tool</strong>.<br>Do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
