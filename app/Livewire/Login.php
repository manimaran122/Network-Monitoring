<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email    = 'admin@monitor.local';
    public $password = 'password';

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            $user = Auth::user();

            // Build the columns to update — only scalars, never touch 'password'
            $updateData = ['last_login_at' => now()];

            // If this is a legacy/seeded user with no password_changed_at
            // AND they are NOT flagged for a forced change,
            // initialise the 45-day countdown starting today.
            if (! $user->password_changed_at && ! $user->must_change_password) {
                $updateData['password_changed_at'] = now();
            }

            // Use updateQuietly so the 'hashed' cast never touches the password column
            $user->updateQuietly($updateData);

            // Refresh the model so must_change_password / isPasswordExpired()
            // are read from the freshly saved DB row (not the cached instance).
            $user = $user->fresh();

            // ── Force-change: first login (admin-created accounts) ─────────────
            if ($user->must_change_password) {
                return redirect()->route('password.change')
                    ->with('force_change_reason', 'first_login');
            }

            // ── Force-change: 45-day expiry ────────────────────────────────────
            if ($user->isPasswordExpired()) {
                return redirect()->route('password.change')
                    ->with('force_change_reason', 'expired');
            }

            // ── Normal login ───────────────────────────────────────────────────
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.app', ['title' => 'Login']);
    }
}
