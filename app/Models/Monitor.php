<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Monitor extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
        'email_notification' => 'boolean',
        'sms_notification' => 'boolean',
        'voice_notification' => 'boolean',
        'push_notification' => 'boolean',
        'last_check' => 'datetime',
        'ssl_expiry' => 'date',
    ];

    public function pingMetrics()
    {
        return $this->hasMany(PingMetric::class);
    }

    public function latestMetric()
    {
        return $this->hasOne(PingMetric::class)->latestOfMany();
    }

    public function recentMetrics()
    {
        return $this->hasMany(PingMetric::class)->orderBy('created_at', 'desc')->limit(20);
    }
    public function scopeActive(Builder $query)
    {
        return $query->where('status', '!=', 'maintenance');
    }

    public function shouldCheck(): bool
    {
        if (!$this->last_check) {
            return true;
        }

        // check_interval is in seconds
        return $this->last_check->addSeconds($this->check_interval ?? 300)->isPast();
    }
}
