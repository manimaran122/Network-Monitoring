<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Alert</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f172a; color: #e2e8f0; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #1e293b; border-radius: 12px; overflow: hidden; border: 1px solid #334155; }
        .header { background: linear-gradient(135deg, #dc2626, #991b1b); padding: 32px 40px; text-align: center; }
        .header .icon { font-size: 48px; margin-bottom: 12px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; color: #fff; letter-spacing: -0.5px; }
        .header p { margin: 8px 0 0; color: #fca5a5; font-size: 14px; }
        .body { padding: 32px 40px; }
        .alert-badge { display: inline-block; background: #dc2626; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        .stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 24px 0; }
        .stat-card { background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: 16px 20px; }
        .stat-card .label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .stat-card .value { font-size: 22px; font-weight: 700; color: #f1f5f9; }
        .stat-card.danger .value { color: #f87171; }
        .stat-card.warn .value { color: #fbbf24; }
        .stat-card.info .value { color: #38bdf8; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #1e293b; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { color: #64748b; }
        .info-row .val { color: #e2e8f0; font-weight: 600; }
        .action-btn { display: block; text-align: center; background: #10b981; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; margin: 28px 0 0; letter-spacing: 0.3px; }
        .footer { padding: 20px 40px; text-align: center; font-size: 12px; color: #475569; border-top: 1px solid #334155; }
        .footer strong { color: #10b981; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="icon">📡</div>
            <h1>Network Alert Detected</h1>
            <p>A monitor has exceeded the 30% packet loss threshold</p>
        </div>

        <div class="body">
            <span class="alert-badge">⚠ High Packet Loss</span>

            <h2 style="margin: 0 0 4px; font-size: 20px; color: #f1f5f9;">{{ $monitor->name }}</h2>
            <p style="margin: 0 0 24px; color: #64748b; font-size: 14px;">{{ $monitor->ip_address }}</p>

            <div class="stat-grid">
                <div class="stat-card danger">
                    <div class="label">Packet Loss</div>
                    <div class="value">{{ $packetLoss }}%</div>
                </div>
                <div class="stat-card {{ $latency > 200 ? 'danger' : ($latency > 100 ? 'warn' : 'info') }}">
                    <div class="label">Latency</div>
                    <div class="value">{{ $latency > 0 ? number_format($latency, 1).'ms' : 'N/A' }}</div>
                </div>
            </div>

            <div style="background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: 16px 20px;">
                <div class="info-row">
                    <span class="key">Monitor Type</span>
                    <span class="val">{{ strtoupper($monitor->type ?? 'PING') }}</span>
                </div>
                <div class="info-row">
                    <span class="key">Current Status</span>
                    <span class="val" style="color: #f87171;">{{ strtoupper($monitor->status) }}</span>
                </div>
                <div class="info-row">
                    <span class="key">Last Checked</span>
                    <span class="val">{{ now()->format('d M Y, H:i:s') }} IST</span>
                </div>
                <div class="info-row">
                    <span class="key">Group</span>
                    <span class="val">{{ $monitor->group ?? 'Default' }}</span>
                </div>
            </div>

            <a href="{{ config('app.url') }}/monitor/{{ $monitor->id }}" class="action-btn">
                View Monitor Details →
            </a>
        </div>

        <div class="footer">
            Sent by <strong>Monitoring Tool</strong> &bull; Automated alert when packet loss ≥ 30%<br>
            {{ config('app.url') }}
        </div>
    </div>
</body>
</html>
