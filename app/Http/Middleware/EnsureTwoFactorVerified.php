<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Redirect users who have 2FA enabled but haven't verified their code
     * in the current session to the 2FA challenge page.
     *
     * Users without 2FA enabled pass through unaffected.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->hasTwoFactorEnabled()
            && ! $request->session()->get('2fa_verified')
        ) {
            // Allow access to the 2FA challenge and verify routes
            if ($request->routeIs('two-factor.challenge', 'two-factor.verify', 'logout')) {
                return $next($request);
            }

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
