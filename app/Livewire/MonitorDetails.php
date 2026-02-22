<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Monitor;
use App\Jobs\RunMonitorCheck;

class MonitorDetails extends Component
{
    public Monitor $monitor;
    public $chartLabels = [];
    public $chartData = [];
    
    // Stats
    public $uptime24h = 100;
    public $uptime7d = 100;
    public $uptime30d = 100;
    
    // Chart Stats
    public $avgLatency = 0;
    public $minLatency = 0;
    public $maxLatency = 0;
    
    // Filter
    public $timeframe = 24; 
    public $customDateStart = null;
    public $customDateEnd = null;

    public function setPreset($preset)
    {
        $this->customDateStart = null;
        $this->customDateEnd = null;

        switch ($preset) {
            case 'this_week':
                $this->customDateStart = now()->startOfWeek()->format('d/m/Y');
                $this->customDateEnd = now()->endOfWeek()->format('d/m/Y');
                break;
            case 'last_week':
                $this->customDateStart = now()->subWeek()->startOfWeek()->format('d/m/Y');
                $this->customDateEnd = now()->subWeek()->endOfWeek()->format('d/m/Y');
                break;
            case 'this_month':
                $this->customDateStart = now()->startOfMonth()->format('d/m/Y');
                $this->customDateEnd = now()->endOfMonth()->format('d/m/Y');
                break;
            case 'last_month':
                $this->customDateStart = now()->subMonth()->startOfMonth()->format('d/m/Y');
                $this->customDateEnd = now()->subMonth()->endOfMonth()->format('d/m/Y');
                break;
            case 'entire_history':
                $this->customDateStart = $this->monitor->created_at->format('d/m/Y');
                $this->customDateEnd = now()->format('d/m/Y');
                break;
        }
        
        $this->dispatch('refresh-charts'); 
    }
    
    public function updatedTimeframe(): void
    {
        // chart-update is dispatched inside render() after data is recalculated
    }

    public function recheckNow(): void
    {
        // Run the check synchronously so the result is available immediately
        RunMonitorCheck::dispatchSync($this->monitor);
        // Refresh model from DB to reflect new status/latency
        $this->monitor->refresh();
        $this->calculateStats();
        session()->flash('recheck', 'Check complete — status updated.');
    }

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // ... (existing logic, irrelevant to this change)
        $this->uptime24h = $this->calculateUptimePercentage(24);
        $this->uptime7d = $this->calculateUptimePercentage(24 * 7);
        $this->uptime30d = $this->calculateUptimePercentage(24 * 30);
    }

    private function calculateUptimePercentage($hours)
    {
        $startTime = now()->subHours($hours);
        $metrics = $this->monitor->pingMetrics()->where('created_at', '>=', $startTime)->get();
        if ($metrics->isEmpty()) {
            return 100;
        }

        // Assuming packet_loss >= 100 or latency == 0 (timeout) means down
        $downChecks = $metrics->filter(fn($m) => $m->packet_loss >= 100 || $m->latency == 0)->count();
        $totalChecks = $metrics->count();

        return round((1 - ($downChecks / $totalChecks)) * 100, 2);
    }

    public function togglePause()
    {
        // Toggle between existing status and 'paused' (maintenance)
        $newStatus = $this->monitor->status === 'maintenance' ? 'online' : 'maintenance'; 
        
        $this->monitor->update(['status' => $newStatus]);
        
        // Dispatch toast notification
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Monitor status updated to ' . ucfirst($newStatus)
        ]);
    }

    public function testNotification()
    {
        // Simulate sending a notification
        // In real app: Notification::send($user, new TestNotification($this->monitor));
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Test notification sent to configured channels.'
        ]);
    }

    public function forcePing()
    {
        RunMonitorCheck::dispatch($this->monitor);
        $this->dispatch('refresh-charts');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Ping command sent to agent.']);
    }

    public function render()
    {
        $timeframe = (int) $this->timeframe;
        $query = $this->monitor->pingMetrics()->latest('created_at');
        $labelFormat = 'H:i';
        $isCustom = false;

        // Custom Date Filter
        if ($this->customDateStart && $this->customDateEnd) {
            $isCustom = true;
            try {
                $start = \Carbon\Carbon::createFromFormat('d/m/Y', $this->customDateStart)->startOfDay();
                $end = \Carbon\Carbon::createFromFormat('d/m/Y', $this->customDateEnd)->endOfDay();
                
                $query->whereBetween('created_at', [$start, $end]);
                
                // Diff in days for label formatting
                $daysDiff = $start->diffInDays($end);
                $labelFormat = $daysDiff > 1 ? 'M d H:i' : 'H:i';
                
            } catch (\Exception $e) {
                // Fallback if format invalid
            }
        } else {
             $query->where('created_at', '>=', now()->subHours($timeframe));
             $labelFormat = $timeframe > 24 ? 'M d H:i' : 'H:i';
        }

        // Smart Data Fetching (Use Aggregation if range > 25h or Custom range > 1 day)
        $shouldAggregate = ($isCustom && isset($daysDiff) && $daysDiff > 1) || (!$isCustom && $timeframe > 25);
        
        if ($shouldAggregate) {
            // Aggregate Logic (Need to rebuild query for aggregation)
             $metrics = \App\Models\PingMetric::selectRaw("
                    DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as date_grp,
                    AVG(latency) as latency,
                    MAX(created_at) as created_at
                ")
                ->where('monitor_id', $this->monitor->id);
                
             if ($isCustom && isset($start) && isset($end)) {
                 $metrics->whereBetween('created_at', [$start, $end]);
             } else {
                 $metrics->where('created_at', '>=', now()->subHours($timeframe));
             }

             $metrics = $metrics->groupByRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')")
                ->orderBy('date_grp', 'asc')
                ->get();

        } else {
             // Detail Logic
             $metrics = $query->limit(2000)->get()->reverse()->values();
        }

        // Ensure we have data even if empty
        if ($metrics->isEmpty()) {
            $metrics = collect([]);
        }

        // Calculate Stats
        $this->avgLatency = $metrics->avg('latency') ?? 0;
        $this->minLatency = $metrics->min('latency') ?? 0;
        $this->maxLatency = $metrics->max('latency') ?? 0;

        // Format for Chart.js
        $this->chartLabels = $metrics->map(function ($m) use ($labelFormat) {
            $date = is_string($m->created_at) ? \Carbon\Carbon::parse($m->created_at) : $m->created_at;
            return $date->format($labelFormat);
        })->toArray();
        $this->chartData = $metrics->map(fn($m) => $m->latency)->toArray();
        
        // 24h Bar Data (Keeping this static for last 24h regardless of filter for now)
        $barData = \App\Models\PingMetric::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as hour, AVG(latency) as avg_latency, AVG(packet_loss) as avg_loss')
            ->where('monitor_id', $this->monitor->id)
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
            
        // Dispatch chart update for Alpine to pick up when timeframe changes
        $this->dispatch('chart-update', [
            'labels' => $this->chartLabels,
            'data'   => $this->chartData,
            'avg'    => $this->avgLatency,
        ]);

        return view('livewire.monitor-details', [
            'metrics' => $metrics,
            'barData' => $barData,
        ]);
    }
}
