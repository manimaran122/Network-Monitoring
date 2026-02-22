<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * Redirects the authenticated user to the change-password page
     * when EITHER:
     *  - must_change_password is true  (first login / admin-reset accounts)
     *  - the password has been expired for more than PASSWORD_EXPIRY_DAYS days
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        $changePasswordRoute = route('password.change');

        // Don't redirect if already on the change-password page or logout
        if ($request->is('password/change') || $request->is('logout')) {
            return $next($request);
        }

        // First-login force change
        if ($user->must_change_password) {
            return redirect($changePasswordRoute)
                ->with('force_change_reason', 'first_login');
        }

        // 45-day expiry force change
        if ($user->isPasswordExpired()) {
            return redirect($changePasswordRoute)
                ->with('force_change_reason', 'expired');
        }

        return $next($request);
    }
}
