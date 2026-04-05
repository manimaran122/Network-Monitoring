<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monitor;
use Carbon\Carbon;

class MonitorSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['online', 'online', 'online', 'offline', 'warning', 'maintenance'];
        
        for ($i = 0; $i < 10; $i++) {
            $status = $statuses[array_rand($statuses)];
            
            Monitor::create([
                'name' => 'Server-' . ($i + 1),
                'ip_address' => '192.168.1.' . rand(1, 254),
                'port' => rand(80, 443),
                'status' => $status,
                'uptime' => rand(80, 100),
                'latency' => rand(5, 150),
                'jitter' => rand(1, 20),
                'packet_loss' => $status === 'offline' ? 100 : rand(0, 5),
                'ssl_expiry' => Carbon::now()->addDays(rand(10, 365)),
                'last_check' => Carbon::now(),
            ]);
        }
    }
}
