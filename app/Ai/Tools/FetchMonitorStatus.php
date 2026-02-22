<?php

namespace App\Ai\Tools;

use App\Models\Monitor;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class FetchMonitorStatus implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Fetches the current status of monitors from the monitoring system. 
                You can filter by status (online, offline, warning, maintenance, pending) 
                and limit the number of results. This tool provides real-time monitoring data 
                including monitor names, URLs, types, and current status.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = Monitor::query();

        // Filter by status if provided
        if (isset($request['status']) && $request['status'] !== 'all') {
            $query->where('status', $request['status']);
        }

        // Apply limit
        $limit = $request['limit'] ?? 10;
        $monitors = $query->limit($limit)->get();

        // Format the response
        $result = [
            'total_monitors' => $monitors->count(),
            'filters_applied' => [
                'status' => $request['status'] ?? 'all',
                'limit' => $limit,
            ],
            'monitors' => $monitors->map(function ($monitor) {
                return [
                    'id' => $monitor->id,
                    'name' => $monitor->name,
                    'url' => $monitor->url,
                    'type' => $monitor->type,
                    'status' => $monitor->status,
                    'interval' => $monitor->interval,
                    'created_at' => $monitor->created_at?->toDateTimeString(),
                    'updated_at' => $monitor->updated_at?->toDateTimeString(),
                ];
            })->toArray(),
            'status_summary' => [
                'online' => Monitor::where('status', 'online')->count(),
                'offline' => Monitor::where('status', 'offline')->count(),
                'warning' => Monitor::where('status', 'warning')->count(),
                'maintenance' => Monitor::where('status', 'maintenance')->count(),
                'pending' => Monitor::where('status', 'pending')->count(),
            ],
        ];

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'status' => $schema->string()
                ->enum(['all', 'online', 'offline', 'warning', 'maintenance', 'pending'])
                ->description('Filter monitors by status. Use "all" to get all monitors.'),
            
            'limit' => $schema->integer()
                ->min(1)
                ->max(100)
                ->description('Maximum number of monitors to return (default: 10, max: 100)'),
        ];
    }
}
