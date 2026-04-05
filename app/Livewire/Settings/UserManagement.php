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
    public string $statusFilter = 'all';
    public int $perPage = 10;

    // Modal State — store ONLY the ID, re-fetch the model when needed
    public bool $isModalOpen  = false;
    public ?int  $editingUserId = null;

    // Delete Confirmation
    public bool $confirmingUserDeletion = false;
    public ?int $userIdBeingDeleted = null;

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
            'role'     => 'required|exists:roles,name',
            'password' => $this->editingUserId ? 'nullable' : 'required|min:8',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
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

    public function confirmUserDeletion(int $userId): void
    {
        if ($userId === auth()->id()) return;
        $this->userIdBeingDeleted = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(): void
    {
        if (!auth()->user()->can('manage_users')) {
            $this->dispatch('user-management-toast', message: "Unauthorized action.", type: 'error');
            return;
        }

        if ($this->userIdBeingDeleted === auth()->id()) return;

        $user = User::find($this->userIdBeingDeleted);
        if ($user) {
            $user->delete();
            $this->dispatch('user-management-toast', message: "User '{$user->name}' deleted successfully.", type: 'success');
            session()->flash('message', "User '{$user->name}' deleted successfully.");
        }

        $this->cancelDeletion();
    }

    public function cancelDeletion(): void
    {
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
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
        if (!auth()->user()->can('manage_users')) {
            $this->dispatch('user-management-toast', message: "Unauthorized action.", type: 'error');
            return;
        }

        $this->validate();

        if ($this->editingUserId) {
            // ── UPDATE existing user ──────────────────────────────────────────
            $user = User::findOrFail($this->editingUserId);
            $user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'role'  => $this->role,
            ]);
            $this->dispatch('user-management-toast', message: 'User updated successfully.', type: 'info');
            session()->flash('message', 'User updated successfully.');
        } else {
            // ── CREATE new user ───────────────────────────────────────────────
            $user = User::create([
                'name'                 => $this->name,
                'email'                => $this->email,
                'password'             => Hash::make($this->password),
                'role'                 => $this->role,
                'status'               => true,
                'must_change_password' => true,
                'password_changed_at'  => null,
            ]);
            $this->dispatch('user-management-toast', message: "User '{$user->name}' created successfully.", type: 'success');
            session()->flash('message', 'User created successfully.');
        }

        $this->closeModal();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->when($this->roleFilter !== 'all', fn ($q) => $q->where('role', $this->roleFilter))
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter === 'active' ? 1 : 0))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.settings.user-management', [
            'users' => $users,
            'allRoles' => \Spatie\Permission\Models\Role::all(),
        ]);
    }
}
