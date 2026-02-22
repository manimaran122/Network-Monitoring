<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Component
{
    public string $current_password      = '';
    public string $password              = '';
    public string $password_confirmation = '';

    /** Reason why this page is shown: 'first_login' | 'expired' | null */
    public ?string $forceReason = null;

    public function mount(): void
    {
        // Only authenticated users can reach this page (middleware enforces it).
        // Read the reason that was flashed into the session by the middleware.
        $this->forceReason = session('force_change_reason');
    }

    protected function rules(): array
    {
        return [
            'current_password'      => ['required'],
            'password'              => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
            'password_confirmation' => ['required'],
        ];
    }

    protected function messages(): array
    {
        return [
            'current_password.required'      => 'Please enter your current password.',
            'password.confirmed'             => 'The new passwords do not match.',
            'password.min'                   => 'Your new password must be at least 8 characters.',
            'password_confirmation.required' => 'Please confirm your new password.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Re-fetch a fresh user instance directly from the DB to avoid stale cache
        $user = Auth::user()->fresh();

        // Verify the current password
        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password you entered is incorrect.');
            return;
        }

        // Prevent reusing the same password
        if (Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Your new password must be different from your current password.');
            return;
        }

        // Persist the changes
        $user->update([
            'password'             => Hash::make($this->password),
            'must_change_password' => false,
            'password_changed_at'  => now(),
        ]);

        // Re-authenticate with the updated user so the session reflects fresh data
        Auth::setUser($user->fresh());

        // Clear the flash reason
        session()->forget('force_change_reason');

        // Flash success message and do a FULL (non-SPA) redirect so the
        // ForcePasswordChange middleware re-evaluates the now-updated user.
        session()->flash('password_changed', 'Your password has been updated successfully.');

        // Use redirect() — NOT navigate:true — to force a real page load
        // so the server middleware sees the updated user state.
        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.change-password')
            ->layout('components.layouts.auth');
    }
}
