<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMonitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:check {id?}';
    protected $description = 'Run a manual check on all monitors or a specific one.';

    public function handle()
    {
        $id = $this->argument('id');
        
        // "run enable all data" - fetching ALL monitors, not just active ones
        $monitors = $id 
            ? \App\Models\Monitor::where('id', $id)->get()
            : \App\Models\Monitor::all();

        $this->info("Checking {$monitors->count()} monitors...");

        foreach ($monitors as $monitor) {
            $this->line("Checking {$monitor->name} ({$monitor->ip_address})...");
            \App\Jobs\RunMonitorCheck::dispatchSync($monitor);
            
            $monitor->refresh();
            $statusColor = $monitor->status === 'online' ? 'green' : 'red';
            $this->line("<fg={$statusColor}>Status: {$monitor->status}, Latency: {$monitor->latency}ms</>");
        }

        $this->info('Done.');
    }
}
