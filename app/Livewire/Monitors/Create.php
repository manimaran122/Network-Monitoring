<?php

namespace App\Livewire\Monitors;

use Livewire\Component;
use App\Models\Monitor;

class Create extends Component
{
    public $monitorType = 'ping';
    public $ip_address = '';
    public $name = '';
    public $group = 'default';
    public $tags = []; 
    public $tagInput = ''; 

    // Notifications
    public $email_notification = true;
    public $sms_notification = true;
    public $voice_notification = true;
    public $push_notification = false;

    protected $rules = [
        'monitorType' => 'required',
        'ip_address' => 'required',
        'name' => 'required|string|max:255',
        'group' => 'required',
    ];

    public function addTag()
    {
        if (!empty($this->tagInput)) {
            if (!in_array($this->tagInput, $this->tags)) {
                $this->tags[] = $this->tagInput;
            }
            $this->tagInput = '';
        }
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function save()
    {
        $this->validate();

        Monitor::create([
            'type' => $this->monitorType,
            'name' => $this->name,
            'ip_address' => $this->ip_address,
            'group' => $this->group,
            'tags' => $this->tags,
            'email_notification' => $this->email_notification,
            'sms_notification' => $this->sms_notification,
            'voice_notification' => $this->voice_notification,
            'push_notification' => $this->push_notification,
            'status' => 'online',
            'uptime' => 100,
            'latency' => 0,
            'jitter' => 0,
            'packet_loss' => 0,
        ]);

        return redirect()->route('monitors');
    }

    public function render()
    {
        return view('livewire.monitors.create')->layout('layouts.app');
    }
}
