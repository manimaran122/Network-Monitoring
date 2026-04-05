<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Settings extends Component
{
    use WithPagination;

    public string $activeTab = 'users'; // 'users' | 'logs' | 'roles'

    // ── Create User form ─────────────────────────────────────────────────────
    public string $name     = '';
    public string $email    = '';
    public string $role     = 'viewer';
    public string $password = '';
    public string $search   = '';

    // ── Edit User form ────────────────────────────────────────────────────────
    public ?int   $editingUserId   = null;
    public string $editName        = '';
    public string $editEmail       = '';
    public string $editRole        = 'viewer';
    public bool   $showEditModal   = false;

    // ── Settings ──────────────────────────────────────────────────────────────
    public bool $registrationEnabled = true;

    // ── Log filters ───────────────────────────────────────────────────────────
    public string $logSearch    = '';
    public string $filterAction = 'all';

    // ── Role CRUD ─────────────────────────────────────────────────────────────
    public string $roleName = '';
    public ?int $editingRoleId = null;
    public bool $showRoleModal = false;
    public ?int $selectedRoleId = null;



    protected $queryString = ['activeTab'];

    public function mount(): void
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access to settings.');
        }

        $reg = DB::table('settings')->where('key', 'registration_enabled')->first();
        $this->registrationEnabled = $reg ? (bool) $reg->value : true;

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // Ensure some base permissions exist for demo
        $perms = ['view_monitors', 'manage_monitors', 'manage_users', 'view_logs', 'manage_settings'];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }
    }

    public function switchTab(string $tab): void
    {
        if ($tab === 'logs' && !auth()->user()->can('view_logs')) {
            $this->dispatch('user-management-toast', message: "Unauthorized tab.", type: 'error');
            return;
        }
        if ($tab === 'roles' && !auth()->user()->can('manage_settings')) {
            $this->dispatch('user-management-toast', message: "Unauthorized tab.", type: 'error');
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function toggleRegistration(): void
    {
        $this->registrationEnabled = !$this->registrationEnabled;
        DB::table('settings')->updateOrInsert(
            ['key' => 'registration_enabled'],
            ['value' => $this->registrationEnabled, 'updated_at' => now()]
        );
        $this->writeLog('UPDATE_SETTING', "Toggled user registration to " . ($this->registrationEnabled ? 'ON' : 'OFF'));
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::findOrFail($userId);
        if ($user->id === Auth::id()) return;

        $user->status = !$user->status;
        $user->save();

        $this->writeLog('UPDATE_USER', "Toggled status for {$user->name}");
    }

    // ── Create User ───────────────────────────────────────────────────────────
    public function createUser(): void
    {
        $this->validate([
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,viewer',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name'                 => $this->name,
            'email'                => $this->email,
            'role'                 => $this->role,
            'password'             => Hash::make($this->password),
            'status'               => true,
            'must_change_password' => true,  // Force password change on first login
            'password_changed_at'  => null,  // Starts after they change it
        ]);

        $this->writeLog('CREATE_USER', "Created user {$user->name} ({$user->email}) — forced to change password on first login");
        $this->reset(['name', 'email', 'role', 'password']);
        $this->dispatch('close-modal');
        session()->flash('message', 'User created. They will be prompted to change their password on first login.');
    }

    // ── Open Edit Modal ───────────────────────────────────────────────────────
    public function openEditModal(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->editName      = $user->name;
        $this->editEmail     = $user->email;
        $this->editRole      = $user->role ?? 'viewer';
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal  = false;
        $this->editingUserId  = null;
        $this->resetValidation();
    }

    // ── Update User ───────────────────────────────────────────────────────────
    public function updateUser(): void
    {
        $this->validate([
            'editName'  => 'required|string|min:3',
            'editEmail' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingUserId)],
            'editRole'  => 'required|in:admin,viewer',
        ]);

        $user = User::findOrFail($this->editingUserId);
        $user->update([
            'name'  => $this->editName,
            'email' => $this->editEmail,
            'role'  => $this->editRole,
        ]);

        $this->writeLog('UPDATE_USER', "Updated user {$user->name} ({$user->email})");
        $this->closeEditModal();
        session()->flash('message', 'User updated successfully.');
    }

    public function updatedLogSearch(): void    { $this->resetPage(); }
    public function updatedFilterAction(): void { $this->resetPage(); }
    public function updatedSearch(): void       { $this->resetPage(); }

    public function clearLogs(): void
    {
        DB::table('activity_logs')->delete();
        $this->writeLog('CLEAR_LOGS', 'Activity logs cleared by admin');
    }

    // ── Role Management ──────────────────────────────────────────────────────────
    public function openRoleModal(?int $id = null): void
    {
        $this->reset(['roleName', 'editingRoleId']);
        $this->resetValidation();
        
        if ($id) {
            $role = Role::findOrFail($id);
            $this->editingRoleId = $role->id;
            $this->roleName      = $role->name;
        }

        $this->showRoleModal = true;
    }

    public function closeRoleModal(): void
    {
        $this->showRoleModal = false;
        $this->editingRoleId = null;
        $this->resetValidation();
    }

    public function saveRole(): void
    {
        $this->validate(['roleName' => 'required|string|min:3|unique:roles,name,' . ($this->editingRoleId ?? 'NULL')]);

        if ($this->editingRoleId) {
            $role = Role::findOrFail($this->editingRoleId);
            $oldName = $role->name;
            $role->update(['name' => strtolower($this->roleName)]);
            $this->writeLog('UPDATE_ROLE', "Renamed role from '{$oldName}' to '{$role->name}'");
            session()->flash('message', 'Role updated successfully.');
        } else {
            $role = Role::create(['name' => strtolower($this->roleName), 'guard_name' => 'web']);
            $this->writeLog('CREATE_ROLE', "Created new role '{$role->name}'");
            session()->flash('message', "Role '{$role->name}' created successfully.");
        }

        $this->closeRoleModal();
    }

    public function deleteRole(int $id): void
    {
        $role = Role::findOrFail($id);
        if (in_array($role->name, ['admin', 'viewer'])) {
            session()->flash('error', 'System roles cannot be deleted.');
            return;
        }

        $role->delete();
        $this->writeLog('DELETE_ROLE', "Deleted role '{$role->name}'");
        session()->flash('message', 'Role deleted successfully.');
    }

    public function togglePermission(int $roleId, string $permissionName): void
    {
        $role = Role::findOrFail($roleId);
        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }
    }
    private function writeLog(string $action, string $details = ''): void
    {
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),

            'action'     => $action,
            'details'    => $details,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'usersPage');

        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select(
                'activity_logs.*',
                DB::raw("COALESCE(users.name, 'Deleted User') as user_name"),
                DB::raw("COALESCE(users.email, '—') as user_email")
            )
            ->when($this->filterAction !== 'all', fn($q) => $q->where('activity_logs.action', $this->filterAction))
            ->when($this->logSearch, fn($q) => $q
                ->where(fn($inner) => $inner
                    ->where('users.name', 'like', "%{$this->logSearch}%")
                    ->orWhere('activity_logs.ip_address', 'like', "%{$this->logSearch}%")
                    ->orWhere('activity_logs.action', 'like', "%{$this->logSearch}%")
                    ->orWhere('activity_logs.details', 'like', "%{$this->logSearch}%")))
            ->orderByDesc('activity_logs.created_at')
            ->paginate(15, ['*'], 'logsPage');

        $logStats = [
            'total'  => DB::table('activity_logs')->count(),
            'today'  => DB::table('activity_logs')->whereDate('created_at', today())->count(),
            'logins' => DB::table('activity_logs')->where('action', 'login')->whereDate('created_at', today())->count(),
        ];
        $allRoles = Role::with('permissions')->get();
        $allPermissions = Permission::all();
        $selectedRole = $this->selectedRoleId ? Role::with('permissions')->find($this->selectedRoleId) : null;

        return view('livewire.settings', compact('users', 'logs', 'logStats', 'allRoles', 'allPermissions', 'selectedRole'));

    }
}
