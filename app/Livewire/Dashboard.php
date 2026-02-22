<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Monitor;

class Dashboard extends Component
{
    public function render()
    {
        $monitors = Monitor::orderByDesc('last_check')->limit(8)->get();

        $total        = Monitor::count();
        $onlineCount  = Monitor::where('status', 'online')->count();
        $criticalCount = Monitor::whereIn('status', ['offline', 'warning'])->count();
        $avgLatencyRaw = $total > 0 ? Monitor::where('status', 'online')->avg('latency') : 0;

        // Dispatch updated chart data on each poll
        $this->dispatch('update-charts', $this->getChartData());

        return view('livewire.dashboard', [
            'monitors'   => $monitors,
            'total'      => $total,
            'online'     => $onlineCount,
            'critical'   => $criticalCount,
            'avgLatency' => number_format($avgLatencyRaw, 1),
            'chartData'  => $this->getChartData(),
        ]);
    }

    private function getChartData(): array
    {
        $now     = now();
        $window  = 60;   // minutes to look back
        $buckets = 20;   // data points (3-min resolution)
        $interval = $window / $buckets;

        $labels  = [];
        $latency = [];
        $jitter  = [];
        $loss    = [];

        // All active monitors for per-monitor breakdown
        $monitors = \App\Models\Monitor::active()->get();

        // Per-monitor latency series (for multi-line chart)
        $monitorSeries = [];
        foreach ($monitors as $m) {
            $monitorSeries[$m->id] = [
                'name'   => $m->name,
                'ip'     => $m->ip_address,
                'data'   => [],
            ];
        }

        $prevAvg      = null;
        $prevLoss     = 0;
        $prevMonitor  = []; // last known value per monitor id

        for ($i = $buckets - 1; $i >= 0; $i--) {
            $end   = $now->copy()->subMinutes($i * $interval);
            $start = $end->copy()->subMinutes($interval);
            $label = $end->format('H:i');
            $labels[] = $label;

            // Aggregate across ALL monitors in this bucket
            $metrics = \App\Models\PingMetric::whereBetween('created_at', [$start, $end])->get();

            if ($metrics->count() > 0) {
                $avgLat  = round($metrics->avg('latency'), 1);
                $avgLoss = round($metrics->avg('packet_loss'), 2);
                $jitter[]  = $prevAvg !== null ? round(abs($avgLat - $prevAvg), 1) : 0;
                $prevAvg   = $avgLat;
                $prevLoss  = $avgLoss;
            } else {
                // Forward-fill: use last known value so chart draws a continuous line
                $avgLat  = $prevAvg;   // null only before the very first real data point
                $avgLoss = $prevLoss;
                $jitter[] = 0;
            }

            $latency[] = $avgLat;
            $loss[]    = $avgLoss;

            // Per-monitor data for this bucket (forward-fill per monitor)
            foreach ($monitors as $m) {
                $mMetrics = $metrics->where('monitor_id', $m->id);
                if ($mMetrics->count() > 0) {
                    $val = round($mMetrics->avg('latency'), 1);
                    $prevMonitor[$m->id] = $val;
                } else {
                    $val = $prevMonitor[$m->id] ?? null;
                }
                $monitorSeries[$m->id]['data'][] = $val;
            }
        }

        // ── BACKFILL leading nulls ─────────────────────────────────────────
        // Forward-fill only covers gaps AFTER the first real value.
        // Backfill fills gaps BEFORE it so the chart always shows a full line.
        $backfill = function (array &$arr): void {
            $first = null;
            foreach ($arr as $v) {
                if ($v !== null) { $first = $v; break; }
            }
            if ($first === null) return; // no data at all — nothing to draw
            foreach ($arr as &$v) {
                if ($v === null) $v = $first; else break; // stop at first real value
            }
        };

        $backfill($latency);
        $backfill($loss);
        foreach ($monitorSeries as &$s) {
            $backfill($s['data']);
        }

        return [
            'labels'         => $labels,
            'latency'        => $latency,
            'jitter'         => $jitter,
            'loss'           => $loss,
            'monitorSeries'  => array_values($monitorSeries),
            'monitorCount'   => $monitors->count(),
        ];
    }
}
