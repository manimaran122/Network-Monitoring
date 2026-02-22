<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;

class DepartmentManagement extends Component
{
    use WithPagination;

    public $name, $description;
    public $departmentId;
    public $isOpen = false;
    public $search = '';

    public function render()
    {
        $departments = Department::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.settings.department-management', [
            'departments' => $departments
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->departmentId = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:departments,name,' . $this->departmentId,
            'description' => 'nullable|string'
        ]);

        Department::updateOrCreate(['id' => $this->departmentId], [
            'name' => $this->name,
            'description' => $this->description
        ]);

        session()->flash('message', 
            $this->departmentId ? 'Department updated successfully.' : 'Department created successfully.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->departmentId = $id;
        $this->name = $department->name;
        $this->description = $department->description;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Department::find($id)->delete();
        session()->flash('message', 'Department deleted successfully.');
    }
}
