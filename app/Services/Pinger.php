<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class Pinger
{
    /**
     * Execute a ping command.
     *
     * @param string $ip
     * @param string|null $interface
     * @return array{latency: float, packet_loss: int}
     */
    public function execute(string $ip, ?string $interface = null): array
    {
        // Linux ping command structure: ping -c 3 -W 1 -I eth0 8.8.8.8
        // -c 3: 3 packets
        // -W 1: 1 second timeout per packet
        // -I interface: Bind to interface
        
        $command = "ping -c 3 -W 1 " . ($interface ? "-I {$interface} " : "") . $ip;

        // Note: This command assumes a Linux environment as per the architecture document.
        // On Windows, the syntax would be different (e.g., -n 3 -w 1000).
        // Since the prompt explicitly asks for the Linux logic, we stick to that.
        
        $result = Process::run($command);
        $output = $result->output();

        $latency = 0.0;
        $packetLoss = 100;

        // Parse latency (avg usually, or just match the last one, or all)
        // Linux output summary: "rtt min/avg/max/mdev = 14.000/14.300/14.800/0.300 ms"
        // Or individual lines: "64 bytes from ... time=14.3 ms"
        // We'll try to find the "avg" from the summary first, or fallback to the last time=.
        
        if (preg_match('/rtt min\/avg\/max\/mdev = [\d\.]+\/([\d\.]+)\//', $output, $matches)) {
            $latency = (float) $matches[1];
        } elseif (preg_match_all('/time=([\d\.]+)\s*ms/', $output, $matches)) {
            // Calculate average from individual responses if summary is missing
            $times = $matches[1];
            if (count($times) > 0) {
                $latency = array_sum($times) / count($times);
            }
        }

        // Parse packet loss
        if (preg_match('/(\d+)% packet loss/', $output, $matches)) {
            $packetLoss = (int) $matches[1];
        }

        return [
            'latency' => $latency,
            'packet_loss' => $packetLoss,
        ];
    }
}
