<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    protected $guarded = [];

    protected $casts = [
        'acknowledged_at' => 'datetime',
        'resolved_at'     => 'datetime',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    // Scopes
    public function scopeOpen($q)       { return $q->where('status', 'open'); }
    public function scopeCritical($q)   { return $q->where('severity', 'critical'); }
    public function scopeWarning($q)    { return $q->where('severity', 'warning'); }

    public function severityColor(): string
    {
        return match($this->severity) {
            'critical' => 'red',
            'warning'  => 'amber',
            default    => 'blue',
        };
    }

    public function typeIcon(): string
    {
        return match($this->type) {
            'offline'      => 'ph-plugs-connected',
            'high_latency' => 'ph-hourglass-high',
            'packet_loss'  => 'ph-wave-sawtooth',
            default        => 'ph-warning-circle',
        };
    }
}
