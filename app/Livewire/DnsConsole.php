<?php

namespace App\Livewire;

use Livewire\Component;

class DnsConsole extends Component
{
    public $isOpen = false;
    public $outputLog = [];
    public $target = 'ACT Fibernet'; // Simplified for demo

    protected $listeners = ['open-dns-console' => 'open'];

    public function open($params = [])
    {
        $this->isOpen = true;
        // Reset log or keep history? Prompt implies reset or new session.
        $this->outputLog = [];
        $this->outputLog[] = ['class' => 'text-emerald-500', 'text' => 'root@monitor-tool:~#'];
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function runCommand($type)
    {
        $cmd = $type === 'flush' ? 'ipconfig /flushdns' : 'ipconfig /renew';
        
        $this->outputLog[] = ['class' => 'text-emerald-500', 'text' => "root@monitor-tool:~# $cmd"];
        $this->outputLog[] = ['class' => 'text-amber-400 animate-pulse', 'text' => '> Executing on host...'];
        
        // Simulating execution time
        sleep(1);
        
        if ($type === 'flush') {
            $this->outputLog[] = ['class' => 'text-slate-300', 'text' => 'Successfully flushed the DNS Resolver Cache.'];
            $this->outputLog[] = ['class' => 'text-emerald-500 font-bold', 'text' => '> Success.'];
        } else {
            $this->outputLog[] = ['class' => 'text-slate-300', 'text' => 'IP Configuration Renewed. IPv4: 192.168.1.105'];
            $this->outputLog[] = ['class' => 'text-emerald-500 font-bold', 'text' => '> Success.'];
        }
        
        $this->outputLog[] = ['class' => 'text-emerald-500', 'text' => 'root@monitor-tool:~# _'];
    }

    public function render()
    {
        return view('livewire.dns-console');
    }
}
