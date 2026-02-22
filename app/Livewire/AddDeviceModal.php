<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Monitor;

class AddDeviceModal extends Component
{
    public $isOpen = false;
    public $friendly_name = '';
    public $ip_address = '';

    protected $listeners = ['open-add-device-modal' => 'open'];

    protected $rules = [
        'friendly_name' => 'required|string|max:255',
        'ip_address' => 'required|ip',
    ];

    public function open()
    {
        $this->isOpen = true;
        $this->reset(['friendly_name', 'ip_address']);
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate();

        Monitor::create([
            'friendly_name' => $this->friendly_name,
            'ip_address' => $this->ip_address,
            'status' => 'up',
            'is_active' => true,
        ]);

        $this->close();
        // Dashboard polling will pick this up automatically
    }

    public function render()
    {
        return view('livewire.add-device-modal');
    }
}
