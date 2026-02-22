<?php
namespace App\Jobs;

use App\Mail\MonitorDownAlert;
use App\Models\Alert;
use App\Models\Monitor;
use App\Models\PingMetric;
use App\Services\MonitorChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RunMonitorCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Monitor $monitor) {}

    /**
     * Execute the job.
     */
    public function handle(MonitorChecker $checker): void
    {
        // Execute the check using the universal checker
        $result = $checker->check($this->monitor);

        $packetLoss = (int)   ($result['packet_loss'] ?? 0);
        $latency    = (float) ($result['latency']     ?? 0);

        // ── Safety override: packet_loss is the ground truth ──────────────────
        // The checker may return 'online' on edge cases (e.g. Windows ping
        // parsing fails on 100% loss). Enforce status from numbers here.
        $newStatus = match (true) {
            $packetLoss >= 100 => 'offline',        // all packets lost → definitely down
            $packetLoss >= 30  => 'warning',        // degraded link
            $latency > 200     => 'warning',        // high latency
            default            => $result['status'],// trust checker for normal cases
        };

        // Save raw metric BEFORE updating monitor so DB is consistent
        PingMetric::create([
            'monitor_id'  => $this->monitor->id,
            'latency'     => $latency,
            'packet_loss' => $packetLoss,
        ]);

        // Store previous status before overwriting
        $previousStatus = $this->monitor->status;

        // Update Monitor with the authoritative status
        $this->monitor->update([
            'status'      => $newStatus,
            'latency'     => $latency,
            'packet_loss' => $packetLoss,
            'last_check'  => now(),
        ]);

        // --- Auto-resolve open alerts when monitor comes back online ---
        if ($newStatus === 'online' && $previousStatus !== 'online') {
            Alert::where('monitor_id', $this->monitor->id)
                ->where('status', 'open')
                ->update(['status' => 'resolved', 'resolved_at' => now()]);
        }

        // --- Create alert when monitor goes offline ---
        if ($newStatus === 'offline' && $previousStatus !== 'offline') {
            Alert::create([
                'monitor_id' => $this->monitor->id,
                'severity'   => 'critical',
                'type'       => 'offline',
                'message'    => "{$this->monitor->name} ({$this->monitor->ip_address}) is unreachable — 100% packet loss.",
                'packet_loss'=> $packetLoss,
                'latency'    => $latency,
            ]);
        }

        // --- Create alert for high packet loss (≥30%) while still "online" ---
        if ($packetLoss >= 30 && $newStatus !== 'offline') {
            $existing = Alert::where('monitor_id', $this->monitor->id)
                ->where('type', 'packet_loss')
                ->where('status', 'open')
                ->exists();

            if (!$existing) {
                Alert::create([
                    'monitor_id' => $this->monitor->id,
                    'severity'   => $packetLoss >= 75 ? 'critical' : 'warning',
                    'type'       => 'packet_loss',
                    'message'    => "{$this->monitor->name} is experiencing {$packetLoss}% packet loss.",
                    'packet_loss'=> $packetLoss,
                    'latency'    => $latency,
                ]);
            }
        }

        // --- Create alert for high latency (>200ms) ---
        if ($latency > 200 && $newStatus === 'online') {
            $existing = Alert::where('monitor_id', $this->monitor->id)
                ->where('type', 'high_latency')
                ->where('status', 'open')
                ->exists();

            if (!$existing) {
                Alert::create([
                    'monitor_id' => $this->monitor->id,
                    'severity'   => 'warning',
                    'type'       => 'high_latency',
                    'message'    => "{$this->monitor->name} has high latency: {$latency}ms (threshold: 200ms).",
                    'packet_loss'=> $packetLoss,
                    'latency'    => $latency,
                ]);
            }
        }

        // --- Send email for packet loss ≥30% ---
        if ($packetLoss >= 30) {
            try {
                Mail::to(config('mail.from.address'))->send(new MonitorDownAlert(
                    monitor: $this->monitor,
                    packetLoss: $packetLoss,
                    latency: $latency,
                ));
                Log::info("Alert email sent for [{$this->monitor->name}] — Packet loss: {$packetLoss}%");
            } catch (\Throwable $e) {
                Log::error("Failed to send alert email for [{$this->monitor->name}]: " . $e->getMessage());
            }
        }
    }
}
