<x-layouts.app title="Two-Factor Authentication">
    <x-page-header
        title="Two-Factor Authentication"
        description="Kelola pengaturan verifikasi dua langkah akun kamu."
    />

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    <path d="m9 12 2 2 4-4" />
                </svg>
            </div>

            <div class="flex-1">
                <h3 class="text-lg font-bold text-slate-900">Two-Factor Authentication Aktif</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Akun kamu dilindungi dengan verifikasi dua langkah. Setiap kali login, kamu perlu memasukkan kode OTP dari aplikasi authenticator.
                </p>

                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    Aktif sejak {{ auth()->user()->two_factor_confirmed_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <div class="mt-6 border-t border-slate-100 pt-6">
            <h4 class="text-sm font-bold text-slate-800">Nonaktifkan Two-Factor Authentication</h4>
            <p class="mt-1 text-xs text-slate-500">
                Masukkan password kamu untuk menonaktifkan 2FA. Ini akan mengurangi keamanan akun.
            </p>

            @if ($errors->any())
                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.destroy') }}" class="mt-4">
                @csrf
                @method('DELETE')

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Masukkan password"
                            class="mt-1 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100"
                        >
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-bold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-100"
                    >
                        Nonaktifkan 2FA
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
