<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Settings extends Component
{
    use WithPagination;

    public string $activeTab = 'users'; // 'users' | 'logs'

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

    protected $queryString = ['activeTab'];

    public function mount(): void
    {
        $reg = DB::table('settings')->where('key', 'registration_enabled')->first();
        $this->registrationEnabled = $reg ? (bool) $reg->value : true;
    }

    public function switchTab(string $tab): void
    {
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

        return view('livewire.settings', compact('users', 'logs', 'logStats'));
    }
}
