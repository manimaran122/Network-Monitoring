<?php

namespace App\Livewire;

use App\Models\Monitor;
use App\Models\PingMetric;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Reports extends Component
{
    // Modal state
    public bool $showExportModal = false;

    // Export config
    public array  $selectedIps   = [];
    public string $dateFrom      = '';
    public string $dateTo        = '';
    public string $reportType    = 'summary';   // summary | latency | packet_loss | full
    public string $exportFormat  = 'csv';       // csv | json
    public string $groupBy       = 'hour';      // raw | hour | day

    // Report table (preview)
    public string $reportFilter  = 'all';
    public string $dateRange     = '24';        // hours

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo   = now()->format('Y-m-d');
    }

    public function openExportModal(): void
    {
        $this->showExportModal = true;
        $this->selectedIps = Monitor::active()->pluck('id')->toArray();
    }

    public function closeExportModal(): void
    {
        $this->showExportModal = false;
    }

    public function toggleIp(int $id): void
    {
        if (in_array($id, $this->selectedIps)) {
            $this->selectedIps = array_values(array_filter($this->selectedIps, fn($i) => $i !== $id));
        } else {
            $this->selectedIps[] = $id;
        }
    }

    public function selectAllIps(): void
    {
        $this->selectedIps = Monitor::pluck('id')->toArray();
    }

    public function clearAllIps(): void
    {
        $this->selectedIps = [];
    }

    public function exportCsv(): StreamedResponse
    {
        $monitors = Monitor::whereIn('id', $this->selectedIps)->get()->keyBy('id');
        $from     = \Carbon\Carbon::parse($this->dateFrom)->startOfDay();
        $to       = \Carbon\Carbon::parse($this->dateTo)->endOfDay();

        $filename = 'network-report-' . $from->format('Ymd') . '-to-' . $to->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($monitors, $from, $to) {
            $handle = fopen('php://output', 'w');

            // Header row based on report type
            $headers = match($this->reportType) {
                'summary'     => ['Monitor Name', 'IP Address', 'Status', 'Avg Latency (ms)', 'Min Latency (ms)', 'Max Latency (ms)', 'Avg Packet Loss (%)', 'Total Checks', 'Uptime %'],
                'latency'     => ['Timestamp', 'Monitor Name', 'IP Address', 'Latency (ms)'],
                'packet_loss' => ['Timestamp', 'Monitor Name', 'IP Address', 'Packet Loss (%)'],
                default       => ['Timestamp', 'Monitor Name', 'IP Address', 'Latency (ms)', 'Packet Loss (%)'],
            };
            fputcsv($handle, $headers);

            if ($this->reportType === 'summary') {
                foreach ($monitors as $monitor) {
                    $metrics = PingMetric::where('monitor_id', $monitor->id)
                        ->whereBetween('created_at', [$from, $to])->get();

                    $total   = $metrics->count();
                    $avgLat  = $total ? round($metrics->avg('latency'), 2) : 0;
                    $minLat  = $total ? round($metrics->min('latency'), 2) : 0;
                    $maxLat  = $total ? round($metrics->max('latency'), 2) : 0;
                    $avgLoss = $total ? round($metrics->avg('packet_loss'), 2) : 0;
                    $uptime  = $total ? round((1 - $metrics->where('packet_loss', '>=', 100)->count() / $total) * 100, 2) : 100;

                    fputcsv($handle, [
                        $monitor->name, $monitor->ip_address, ucfirst($monitor->status),
                        $avgLat, $minLat, $maxLat, $avgLoss, $total, $uptime,
                    ]);
                }
            } else {
                $query = PingMetric::with('monitor')
                    ->whereIn('monitor_id', $monitors->keys()->toArray())
                    ->whereBetween('created_at', [$from, $to])
                    ->orderBy('created_at');

                if ($this->groupBy === 'hour') {
                    $query->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as ts, monitor_id, AVG(latency) as latency, AVG(packet_loss) as packet_loss")
                          ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00'), monitor_id");
                } elseif ($this->groupBy === 'day') {
                    $query->selectRaw("DATE(created_at) as ts, monitor_id, AVG(latency) as latency, AVG(packet_loss) as packet_loss")
                          ->groupByRaw("DATE(created_at), monitor_id");
                } else {
                    $query->select('created_at as ts', 'monitor_id', 'latency', 'packet_loss');
                }

                foreach ($query->cursor() as $row) {
                    $monitor = $monitors[$row->monitor_id] ?? null;
                    $name    = $monitor?->name ?? 'Unknown';
                    $ip      = $monitor?->ip_address ?? '—';
                    $ts      = is_string($row->ts) ? $row->ts : $row->ts?->format('Y-m-d H:i:s');

                    $line = match($this->reportType) {
                        'latency'     => [$ts, $name, $ip, round($row->latency, 2)],
                        'packet_loss' => [$ts, $name, $ip, round($row->packet_loss, 2)],
                        default       => [$ts, $name, $ip, round($row->latency, 2), round($row->packet_loss, 2)],
                    };
                    fputcsv($handle, $line);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function render()
    {
        $monitors = Monitor::orderBy('name')->get();

        // Recent metrics table
        $hours    = (int) $this->dateRange;
        $metrics  = PingMetric::with('monitor')
            ->when($this->reportFilter !== 'all', fn($q) => $q->whereHas('monitor', fn($mq) => $mq->where('status', $this->reportFilter)))
            ->where('created_at', '>=', now()->subHours($hours))
            ->latest('created_at')
            ->limit(100)
            ->get();

        // Summary stats per monitor for table
        $summaryRows = Monitor::withCount(['pingMetrics as check_count' => fn($q) => $q->where('created_at', '>=', now()->subHours($hours))])
            ->withAvg(['pingMetrics as avg_latency' => fn($q) => $q->where('created_at', '>=', now()->subHours($hours))], 'latency')
            ->withAvg(['pingMetrics as avg_loss' => fn($q) => $q->where('created_at', '>=', now()->subHours($hours))], 'packet_loss')
            ->orderBy('name')
            ->get();

        return view('livewire.reports', [
            'monitors'    => $monitors,
            'metrics'     => $metrics,
            'summaryRows' => $summaryRows,
        ]);
    }
}
