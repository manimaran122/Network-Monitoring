<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Password policy constants.
     */
    const PASSWORD_EXPIRY_DAYS    = 45;
    const PASSWORD_WARNING_DAYS   = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
        'must_change_password',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'last_login_at'        => 'datetime',
            'status'               => 'boolean',
            'must_change_password' => 'boolean',
            'password_changed_at'  => 'datetime',
        ];
    }

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return \Illuminate\Support\Str::of($this->name)
            ->explode(' ')
            ->map(fn ($segment) => $segment[0] ?? '')
            ->take(2)
            ->implode('');
    }

    // ─────────────────────────────────────────────
    //  Password Policy Helpers
    // ─────────────────────────────────────────────

    /**
     * How many days remain before this user's password expires.
     * Returns null if password_changed_at is not set.
     */
    public function passwordDaysRemaining(): ?int
    {
        if (! $this->password_changed_at) {
            return null;
        }

        $expiredAt = $this->password_changed_at->copy()->addDays(self::PASSWORD_EXPIRY_DAYS);
        $remaining = now()->diffInDays($expiredAt, false); // negative = already expired

        return (int) $remaining;
    }

    /**
     * Has this user's password already expired?
     */
    public function isPasswordExpired(): bool
    {
        if (! $this->password_changed_at) {
            // Never set → treat as expired if they don't need to force-change
            return false;
        }

        return $this->password_changed_at->copy()->addDays(self::PASSWORD_EXPIRY_DAYS)->isPast();
    }

    /**
     * Should we show the "password will expire soon" warning?
     * True when there are 10 or fewer days remaining (but not yet expired).
     */
    public function shouldShowPasswordWarning(): bool
    {
        $days = $this->passwordDaysRemaining();

        if ($days === null) {
            return false;
        }

        return $days >= 0 && $days <= self::PASSWORD_WARNING_DAYS;
    }
}
