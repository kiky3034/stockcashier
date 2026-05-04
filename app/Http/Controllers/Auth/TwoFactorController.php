<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLog,
    ) {
    }

    /**
     * Show the 2FA setup page (generate secret, display it).
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        // If 2FA is already enabled and confirmed, show the status page
        if ($user->hasTwoFactorEnabled()) {
            return view('auth.two-factor.status', [
                'enabled' => true,
            ]);
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        // Store secret temporarily in session until confirmed
        $request->session()->put('2fa_setup_secret', $secret);

        $otpauthUrl = $google2fa->getQRCodeUrl(
            config('app.name', 'StockCashier'),
            $user->email,
            $secret,
        );

        return view('auth.two-factor.setup', [
            'secret' => $secret,
            'otpauthUrl' => $otpauthUrl,
        ]);
    }

    /**
     * Confirm and enable 2FA by verifying the code from the authenticator app.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        $secret = $request->session()->get('2fa_setup_secret');

        if (! $secret) {
            return redirect()
                ->route('two-factor.create')
                ->with('error', 'Sesi setup 2FA sudah kadaluarsa. Silakan mulai ulang.');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->input('code'));

        if (! $valid) {
            return back()->withErrors([
                'code' => 'Kode OTP tidak valid. Pastikan kamu memasukkan kode dari aplikasi authenticator.',
            ]);
        }

        // Generate recovery codes
        $recoveryCodes = collect(range(1, 8))->map(function () {
            return strtoupper(bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(4)));
        })->all();

        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ]);

        $request->session()->forget('2fa_setup_secret');

        $this->activityLog->log(
            event: 'two_factor_enabled',
            description: 'Two-Factor Authentication diaktifkan untuk: ' . $user->email,
            subject: $user,
            user: $user,
        );

        return redirect()
            ->route('two-factor.create')
            ->with('success', 'Two-Factor Authentication berhasil diaktifkan!')
            ->with('recovery_codes', $recoveryCodes);
    }

    /**
     * Disable 2FA.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! \Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        // Clear 2FA session verification flag
        $request->session()->forget('2fa_verified');

        $this->activityLog->log(
            event: 'two_factor_disabled',
            description: 'Two-Factor Authentication dinonaktifkan untuk: ' . $user->email,
            subject: $user,
            user: $user,
        );

        return redirect()
            ->route('two-factor.create')
            ->with('success', 'Two-Factor Authentication berhasil dinonaktifkan.');
    }

    /**
     * Show the 2FA challenge page (enter code after login).
     */
    public function challenge(): View
    {
        return view('auth.two-factor.challenge');
    }

    /**
     * Verify the 2FA challenge code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();
        $code = $request->input('code');

        // Check if it's a recovery code
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
        $recoveryIndex = array_search($code, $recoveryCodes);

        if ($recoveryIndex !== false) {
            // Use and remove the recovery code
            unset($recoveryCodes[$recoveryIndex]);
            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ]);

            $request->session()->put('2fa_verified', true);

            $this->activityLog->log(
                event: 'two_factor_recovery_used',
                description: 'Recovery code digunakan untuk login: ' . $user->email,
                subject: $user,
                user: $user,
            );

            return redirect()->intended(route('dashboard'));
        }

        // Verify as TOTP code
        if (strlen($code) !== 6 || ! ctype_digit($code)) {
            return back()->withErrors([
                'code' => 'Kode OTP tidak valid.',
            ]);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $valid = $google2fa->verifyKey($secret, $code);

        if (! $valid) {
            return back()->withErrors([
                'code' => 'Kode OTP tidak valid. Coba lagi.',
            ]);
        }

        $request->session()->put('2fa_verified', true);

        return redirect()->intended(route('dashboard'));
    }
}
