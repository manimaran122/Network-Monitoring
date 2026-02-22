<?php

namespace App\Livewire\Monitors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Monitor;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $selected = [];

    // Edit modal state
    public bool $showEditModal = false;
    public ?int $editId = null;
    public string $editName = '';
    public string $editIp = '';
    public string $editGroup = 'default';
    public string $editType = 'ping';
    public int    $editPort = 80;
    public int    $editInterval = 300;
    public bool   $editEmail = true;
    public bool   $editSms = false;
    public bool   $editVoice = false;
    public bool   $editPush = false;

    protected function editRules(): array
    {
        return [
            'editName'     => 'required|string|max:255',
            'editIp'       => 'required|string|max:255',
            'editGroup'    => 'required|string|max:100',
            'editType'     => 'required|in:ping,http,port,keyword',
            'editPort'     => 'required|integer|min:1|max:65535',
            'editInterval' => 'required|integer|min:30',
        ];
    }

    public function updatedSearch()        { $this->resetPage(); }
    public function updatedStatusFilter()  { $this->resetPage(); }

    public function delete(int $id): void
    {
        Monitor::find($id)?->delete();
        session()->flash('message', 'Monitor deleted.');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selected)) return;
        Monitor::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        session()->flash('message', 'Monitors deleted.');
    }

    public function editMonitor(int $id): void
    {
        $m = Monitor::findOrFail($id);
        $this->editId       = $m->id;
        $this->editName     = $m->name;
        $this->editIp       = $m->ip_address;
        $this->editGroup    = $m->group ?? 'default';
        $this->editType     = $m->type ?? 'ping';
        $this->editPort     = $m->port ?? 80;
        $this->editInterval = $m->check_interval ?? 300;
        $this->editEmail    = (bool) $m->email_notification;
        $this->editSms      = (bool) $m->sms_notification;
        $this->editVoice    = (bool) $m->voice_notification;
        $this->editPush     = (bool) $m->push_notification;
        $this->showEditModal = true;
    }

    public function updateMonitor(): void
    {
        $this->validate($this->editRules());

        Monitor::where('id', $this->editId)->update([
            'name'               => $this->editName,
            'ip_address'         => $this->editIp,
            'group'              => $this->editGroup,
            'type'               => $this->editType,
            'port'               => $this->editPort,
            'check_interval'     => $this->editInterval,
            'email_notification' => $this->editEmail,
            'sms_notification'   => $this->editSms,
            'voice_notification' => $this->editVoice,
            'push_notification'  => $this->editPush,
        ]);

        $this->cancelEdit();
        session()->flash('message', 'Monitor updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->showEditModal = false;
        $this->editId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Monitor::query()
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('ip_address', 'like', '%'.$this->search.'%'))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc');

        $monitors = $query->paginate(10);

        $counts = [
            'all'     => Monitor::count(),
            'online'  => Monitor::where('status', 'online')->count(),
            'offline' => Monitor::where('status', 'offline')->count(),
        ];

        return view('livewire.monitors.index', compact('monitors', 'counts'));
    }
}
