<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Monitor;

class AddMonitor extends Component
{
    public $isOpen = false;
    public $monitorType = 'ping';
    public $ip_address = '';
    public $name = '';
    public $group = 'default';
    public $tags = []; 
    public $tagInput = ''; 
    
    public $availableMonitorTypes = [
        [
            'id' => 'http',
            'name' => 'HTTP / website monitoring',
            'description' => 'Use HTTP(S) monitor to monitor your website, API endpoint, or anything running on HTTP.',
            'icon' => 'ph-globe',
        ],
        [
            'id' => 'keyword',
            'name' => 'Keyword monitoring',
            'description' => "Check the presence or absence of specific text in the request's response body (typically HTML or JSON).",
            'icon' => 'ph-key',
        ],
        [
            'id' => 'ping',
            'name' => 'Ping monitoring',
            'description' => 'Make sure your server or any device in the network is always available.',
            'icon' => 'ph-target',
        ],
        [
            'id' => 'port',
            'name' => 'Port monitoring',
            'description' => 'Monitor any service on your server. Useful for SMTP, POP3, FTP, and other services running on specific TCP ports.',
            'icon' => 'ph-plugs',
        ],
    ]; 

    // Advanced Settings
    public $port = 80;
    public $keyword = '';
    public $keyword_should_exist = true;
    public $check_interval = 300;
    public $request_timeout = 30;
    public $http_method = 'GET';
    public $accepted_status_codes = '200-299';
    public $auth_user = '';
    public $auth_pass = '';
    public $verify_ssl = true;
    public $follow_redirects = true;

    // Notifications
    public $email_notification = true;
    public $sms_notification = false;
    public $voice_notification = false;
    public $push_notification = false;

    protected $listeners = ['open-add-monitor' => 'open'];

    protected $rules = [
        'monitorType' => 'required',
        'ip_address' => 'required',
        'name' => 'required|string|max:255',
        'group' => 'required',
    ];

    public function open()
    {
        $this->isOpen = true;
        $this->reset([
            'name', 'ip_address', 'tags', 'tagInput', 'monitorType', 'group',
            'port', 'keyword', 'keyword_should_exist',
            'check_interval', 'request_timeout', 'http_method', 'accepted_status_codes',
            'auth_user', 'auth_pass', 'verify_ssl', 'follow_redirects'
        ]);
    }

    public function close()
    {
        $this->isOpen = false;
    }

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

        $monitor = Monitor::create([
            'type' => $this->monitorType,
            'name' => $this->name,
            'ip_address' => $this->ip_address,
            'group' => $this->group,
            'tags' => $this->tags,
            'email_notification' => $this->email_notification,
            'sms_notification' => $this->sms_notification,
            'voice_notification' => $this->voice_notification,
            'push_notification' => $this->push_notification,
            'status' => 'pending', // Set to pending initially
            'uptime' => 0.0,
            'latency' => 0,
            'jitter' => 0,
            'packet_loss' => 0,
            
            // Conditional
            'port' => ($this->monitorType === 'port') ? $this->port : 80,
            'keyword' => ($this->monitorType === 'keyword') ? $this->keyword : null,
            'keyword_should_exist' => ($this->monitorType === 'keyword') ? $this->keyword_should_exist : true,
            
            // Advanced / New Fields
            'check_interval' => $this->check_interval,
            'request_timeout' => $this->request_timeout,
            'method' => $this->http_method,
            'accepted_status_codes' => $this->accepted_status_codes,
            'authentication_user' => $this->auth_user,
            'authentication_password' => $this->auth_pass,
            'verify_ssl' => $this->verify_ssl,
            'follow_redirects' => $this->follow_redirects,
        ]);

        // Perform immediate check
        \App\Jobs\RunMonitorCheck::dispatchSync($monitor);

        $this->close();
        $this->dispatch('monitorAdded'); // Event for Dashboard refresh
        return redirect()->route('monitors');
    }

    public function render()
    {
        return view('livewire.add-monitor');
    }
}
