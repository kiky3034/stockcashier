<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Services\ActivityLogService;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();
        
        $user = $request->user();
        
        $activityLog->log(
            event: 'user_logged_in',
            description: 'User login: ' . $user->email,
            subject: $user,
            properties: [
                'email' => $user->email,
            ],
            user: $user,
        );

        // Tentukan redirect berdasarkan role
        $redirectUrl = $this->getRedirectUrlByRole($user);

        return redirect()
            ->to($redirectUrl)
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

    public function destroy(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $user = $request->user();

        $activityLog->log(
            event: 'user_logged_out',
            description: 'User logout: ' . $user->email,
            subject: $user,
            properties: [
                'email' => $user->email,
            ],
            user: $user,
        );
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

    /**
     * Get redirect URL based on user role
     */
    protected function getRedirectUrlByRole($user): string
    {
        if ($user->hasRole('admin')) {
            return route('admin.dashboard');
        }
        
        if ($user->hasRole('owner')) {
            return route('owner.dashboard');
        }
        
        if ($user->hasRole('warehouse staff')) {
            return route('warehouse.dashboard');
        }
        
        if ($user->hasRole('cashier')) {
            return route('cashier.dashboard');
        }
        
        return route('dashboard');
    }
}