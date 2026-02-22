<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $roleFilter = 'all';

    // Modal State — store ONLY the ID, re-fetch the model when needed
    public bool $isModalOpen  = false;
    public ?int  $editingUserId = null;

    // Form Fields
    public string $name     = '';
    public string $email    = '';
    public string $role     = 'viewer';
    public string $password = ''; // Only for creation

    // ─────────────────────────────────────────────
    //  Computed helper: resolve the editing user
    // ─────────────────────────────────────────────
    private function getEditingUser(): ?User
    {
        return $this->editingUserId ? User::find($this->editingUserId) : null;
    }

    protected function rules(): array
    {
        return [
            'name'     => 'required|string|min:3',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($this->editingUserId)],
            'role'     => 'required|in:admin,viewer',
            'password' => $this->editingUserId ? 'nullable' : 'required|min:8',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(int $userId): void
    {
        if ($userId === auth()->id()) return; // Prevent disabling self

        $user = User::find($userId);
        if ($user) {
            $user->status = ! $user->status;
            $user->save();
        }
    }

    public function openModal(?int $id = null): void
    {
        $this->reset(['name', 'email', 'role', 'password', 'editingUserId']);
        $this->resetValidation();
        $this->role = 'viewer';

        if ($id) {
            $user = User::findOrFail($id);
            $this->editingUserId = $user->id;
            $this->name          = $user->name;
            $this->email         = $user->email;
            $this->role          = $user->role;
        }

        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingUserId) {
            // ── UPDATE existing user ──────────────────────────────────────────
            $user = User::findOrFail($this->editingUserId);
            $user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ]);
            session()->flash('message', 'User updated successfully.');
        } else {
            // ── CREATE new user ───────────────────────────────────────────────
            User::create([
                'name'                 => $this->name,
                'email'                => $this->email,
                'password'             => Hash::make($this->password),
                'role'                 => $this->role,
                'status'               => true,
                'must_change_password' => true,   // Force password change on first login
                'password_changed_at'  => null,   // Starts after they change it
            ]);
            session()->flash('message', 'User created successfully. They will be prompted to change their password on first login.');
        }

        $this->closeModal();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->when($this->roleFilter !== 'all', fn ($q) => $q->where('role', $this->roleFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.settings.user-management', [
            'users' => $users,
        ]);
    }
}
