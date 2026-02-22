<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManager extends Component
{
    public $roles;
    public $name;
    public $permissions = [];
    public $selectedPermissions = [];
    public $roleId;
    public $isOpen = false;

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $this->roles = Role::with('permissions')->get();
        return view('livewire.roles.role-manager')->layout('layouts.app');
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
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->roleId = '';
        $this->selectedPermissions = [];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
        ]);

        $role = Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $this->name
        ]);

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('message', 
            $this->roleId ? 'Role updated successfully.' : 'Role created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->openModal();
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        session()->flash('message', 'Role deleted successfully.');
    }
}
