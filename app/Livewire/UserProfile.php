<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserProfile extends Component
{
    public $isOpen = false;
    public $activeTab = 'account';
    
    // Password Update
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    // MFA
    public $mfa_enabled = false;

    protected $listeners = ['open-user-profile' => 'open'];

    public function mount()
    {
        if (Auth::check()) {
            $this->mfa_enabled = Auth::user()->mfa_enabled;
        }
    }

    public function open()
    {
        $this->isOpen = true;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->forceFill([
            'password' => Hash::make($this->new_password),
        ])->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('status', 'Password updated successfully.');
    }

    public function toggleMfa()
    {
        $user = Auth::user();
        $user->mfa_enabled = !$user->mfa_enabled;
        $user->save();
        $this->mfa_enabled = $user->mfa_enabled;
    }

    public function logoutSession($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
        $this->dispatch('session-invalidated');
    }

    public function render()
    {
        $sessions = collect();
        if (Auth::check()) {
            $sessions = DB::table('sessions')
                ->where('user_id', Auth::id())
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    return (object) [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'is_current_device' => $session->id === session()->getId(),
                        'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                });
        }

        return view('livewire.user-profile', [
            'user' => Auth::user(),
            'sessions' => $sessions,
        ]);
    }
}
