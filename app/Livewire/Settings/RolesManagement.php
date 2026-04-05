<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolesManagement extends Component
{
    public string $activeRoleTab = 'admin'; // Current visible role's permissions
    public ?int $selectedRoleId = null;
    
    // Role CRUD
    public string $newRoleName = '';
    public bool $showCreateModal = false;
    public ?int $editingRoleId = null;
    public string $editingRoleName = '';

    public function mount(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Initialize default roles and permissions if they don't exist
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        $perms = [
            'view_dashboard', 'view_monitors', 'manage_monitors', 
            'view_users', 'manage_users', 'view_logs', 'clear_logs',
            'view_settings', 'manage_settings', 'manage_roles',
            'view_reports', 'manage_alerts'
        ];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Set initial selected role
        $firstRole = Role::where('name', 'admin')->first();
        if ($firstRole) {
            $this->selectedRoleId = $firstRole->id;
        }
    }

    public function selectRole(int $id): void
    {
        $this->selectedRoleId = $id;
    }

    public function togglePermission(string $permissionName): void
    {
        $role = Role::findOrFail($this->selectedRoleId);
        
        if ($role->name === 'admin' && $permissionName === 'manage_settings') {
            // Prevent removing core settings permission from admin
            return;
        }

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }

        $this->dispatch('roles-toast', message: "Permissions updated for " . strtoupper($role->name), type: 'info');
    }

    public function createRole(): void
    {
        $this->validate(['newRoleName' => 'required|string|min:3|unique:roles,name']);

        $role = Role::create([
            'name' => strtolower($this->newRoleName),
            'guard_name' => 'web'
        ]);

        $this->newRoleName = '';
        $this->showCreateModal = false;
        $this->selectedRoleId = $role->id;
        
        $this->dispatch('roles-toast', message: "New role '" . strtoupper($role->name) . "' created successfully.");
    }

    public function editRole(int $id): void
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['admin', 'viewer'])) {
             $this->dispatch('roles-toast', message: "System roles cannot be renamed.", type: 'error');
             return;
        }
        $this->editingRoleId = $role->id;
        $this->editingRoleName = $role->name;
    }

    public function updateRole(): void
    {
        $this->validate(['editingRoleName' => 'required|string|min:3|unique:roles,name,' . $this->editingRoleId]);
        
        $role = Role::findOrFail($this->editingRoleId);
        $role->update(['name' => strtolower($this->editingRoleName)]);
        
        $this->editingRoleId = null;
        $this->dispatch('roles-toast', message: "Role renamed successfully.");
    }

    public function deleteRole(int $id): void
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['admin', 'viewer'])) {
             $this->dispatch('roles-toast', message: "System roles cannot be deleted.", type: 'error');
             return;
        }

        if ($this->selectedRoleId === $role->id) {
            $this->selectedRoleId = Role::where('name', 'admin')->first()->id;
        }

        $role->delete();
        $this->dispatch('roles-toast', message: "Role deleted successfully.");
    }

    public function render()
    {
        return view('livewire.settings.roles-management', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
            'selectedRole' => $this->selectedRoleId ? Role::with('permissions')->find($this->selectedRoleId) : null,
        ])->layout('layouts.app');
    }
}
