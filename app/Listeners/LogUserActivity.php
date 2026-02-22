<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class LogUserActivity
{
    public function handle($event): void
    {
        $action = match (get_class($event)) {
            Login::class => 'login',
            Logout::class => 'logout',
            default => 'unknown',
        };

        if ($event->user) {
            if ($action === 'login') {
                $event->user->update(['last_login_at' => now()]);
            }

            DB::table('activity_logs')->insert([
                'user_id' => $event->user->id,
                'action' => $action,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function subscribe($events): array
    {
        return [
            Login::class => 'handle',
            Logout::class => 'handle',
        ];
    }
}
