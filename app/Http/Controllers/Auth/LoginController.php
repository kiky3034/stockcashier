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
        $activityLog->log(
            event: 'user_logged_in',
            description: 'User login: ' . $request->user()->email,
            subject: $request->user(),
            properties: [
                'email' => $request->user()->email,
            ],
            user: $request->user(),
        );

        return redirect()->intended(route('dashboard'));
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

        return redirect()->route('login');
    }
}