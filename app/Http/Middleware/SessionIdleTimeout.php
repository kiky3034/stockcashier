<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionIdleTimeout
{
    /**
     * Maximum idle time in minutes before the session is considered expired.
     * Configurable via SESSION_IDLE_TIMEOUT in .env (default: 15 minutes).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idleTimeout = (int) config('session.idle_timeout', 15);

        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_time');

            if ($lastActivity && (time() - $lastActivity) > ($idleTimeout * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->with('warning', 'Sesi kamu telah berakhir karena tidak ada aktivitas selama ' . $idleTimeout . ' menit. Silakan login kembali.');
            }

            $request->session()->put('last_activity_time', time());
        }

        return $next($request);
    }
}
