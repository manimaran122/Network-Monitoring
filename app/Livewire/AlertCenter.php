<?php

namespace App\Livewire;

use App\Models\Alert;
use Livewire\Component;
use Livewire\WithPagination;

class AlertCenter extends Component
{
    use WithPagination;

    public string $filter    = 'open';      // open | acknowledged | resolved | all
    public string $severity  = 'all';       // all | critical | warning | info
    public string $search    = '';

    public function acknowledge(int $id): void
    {
        $alert = Alert::findOrFail($id);
        $alert->update([
            'status'          => 'acknowledged',
            'acknowledged_by' => auth()->id(),
            'acknowledged_at' => now(),
        ]);
        $this->dispatch('alert-updated');
    }

    public function resolve(int $id): void
    {
        Alert::findOrFail($id)->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);
        $this->dispatch('alert-updated');
    }

    public function acknowledgeAll(): void
    {
        Alert::open()->update([
            'status'          => 'acknowledged',
            'acknowledged_by' => auth()->id(),
            'acknowledged_at' => now(),
        ]);
    }

    public function updatedFilter(): void  { $this->resetPage(); }
    public function updatedSeverity(): void { $this->resetPage(); }
    public function updatedSearch(): void   { $this->resetPage(); }

    public function render()
    {
        $query = Alert::with('monitor')
            ->when($this->filter !== 'all',   fn($q) => $q->where('status', $this->filter))
            ->when($this->severity !== 'all', fn($q) => $q->where('severity', $this->severity))
            ->when($this->search, fn($q) => $q->whereHas('monitor', fn($mq) =>
                $mq->where('name', 'like', "%{$this->search}%")
                   ->orWhere('ip_address', 'like', "%{$this->search}%")
            ))
            ->latest();

        $allOpen     = Alert::open()->count();
        $critical    = Alert::open()->critical()->count();
        $warning     = Alert::open()->warning()->count();
        $resolved    = Alert::where('status', 'resolved')->whereDate('resolved_at', today())->count();

        return view('livewire.alert-center', [
            'alerts'       => $query->paginate(15),
            'openCount'    => $allOpen,
            'criticalCount'=> $critical,
            'warningCount' => $warning,
            'resolvedToday'=> $resolved,
        ]);
    }
}
