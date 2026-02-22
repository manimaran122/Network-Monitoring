<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class ForgotPassword extends Component
{
    // ── Step tracking: 1=email, 2=otp, 3=new password ────────────────────────
    public int $step = 1;

    // Step 1
    public string $email = '';

    // Step 2
    public string $otp        = '';
    public bool   $otpVerified = false;

    // Step 3
    public string $password              = '';
    public string $password_confirmation = '';

    // Display helpers
    public string $maskedEmail    = '';
    public bool   $resendCooldown = false;
    public int    $resendSeconds  = 0;

    /** Step 1 — Validate email, generate OTP, send mail */
    public function sendOtp(): void
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with that email address.',
        ]);

        $user = User::where('email', $this->email)->first();

        // Generate a 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in DB (clear any old ones for this email first)
        DB::table('password_reset_otps')->where('email', $this->email)->delete();
        DB::table('password_reset_otps')->insert([
            'email'      => $this->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log the OTP email (log driver — appears in storage/logs/laravel.log)
        Mail::to($this->email)->send(new OtpMail(
            otp: $otp,
            userName: $user->name,
            expiresInMinutes: 10,
        ));

        // Mask email for display: m***n@domain.com
        $parts   = explode('@', $this->email);
        $name    = $parts[0];
        $domain  = $parts[1] ?? '';
        $masked  = substr($name, 0, 1) . str_repeat('*', max(1, strlen($name) - 2)) . substr($name, -1);
        $this->maskedEmail = $masked . '@' . $domain;

        $this->step = 2;
        $this->otp  = '';
    }

    /** Step 2 — Verify OTP */
    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $record = DB::table('password_reset_otps')
            ->where('email', $this->email)
            ->where('otp', $this->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            $this->addError('otp', 'Invalid or expired OTP. Please try again or request a new one.');
            return;
        }

        $this->otpVerified = true;
        $this->step        = 3;
    }

    /** Step 2 — Resend OTP (reuse sendOtp logic) */
    public function resendOtp(): void
    {
        $this->step = 1; // Go back to step 1 momentarily to re-validate
        $this->sendOtp();
    }

    /** Step 3 — Set new password */
    public function resetPassword(): void
    {
        if (! $this->otpVerified) {
            $this->step = 1;
            return;
        }

        $this->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.confirmed' => 'The passwords do not match.',
        ]);

        $user = User::where('email', $this->email)->firstOrFail();

        // Prevent re-using same password
        if (Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Your new password must be different from your current password.');
            return;
        }

        // Update password and clear force-change flags
        $user->update([
            'password'             => Hash::make($this->password),
            'must_change_password' => false,
            'password_changed_at'  => now(),
        ]);

        // Clean up OTP record
        DB::table('password_reset_otps')->where('email', $this->email)->delete();

        // Redirect to login with success message
        session()->flash('password_reset_success', 'Password reset successfully. You can now sign in.');
        $this->redirect(route('login'));
    }

    /** Go back to previous step */
    public function goBack(): void
    {
        if ($this->step > 1) {
            $this->step--;
            $this->resetValidation();
        }
    }

    public function render()
    {
        return view('livewire.forgot-password')
            ->layout('components.layouts.auth');
    }
}
