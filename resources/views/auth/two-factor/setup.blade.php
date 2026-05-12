<x-layouts.app title="Two-Factor Authentication">
    <x-page-header
        title="Two-Factor Authentication"
        description="Tingkatkan keamanan akun dengan verifikasi dua langkah."
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

    @if (session('error'))
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
        </div>
    @endif

    {{-- Recovery Codes (shown once after enabling 2FA) --}}
    @if (session('recovery_codes'))
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-amber-800">Simpan Recovery Codes</div>
                    <p class="mt-1 text-sm text-amber-700">
                        Simpan kode-kode berikut di tempat yang aman. Kamu bisa menggunakan recovery code untuk login jika kehilangan akses ke aplikasi authenticator.
                    </p>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        @foreach (session('recovery_codes') as $code)
                            <div class="rounded-xl bg-white px-3 py-2 text-center font-mono text-sm font-bold text-amber-900 ring-1 ring-amber-200">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-900">Setup Authenticator App</h3>
            <p class="mt-1 text-sm text-slate-500">
                Gunakan aplikasi seperti Google Authenticator, Authy, atau 1Password untuk scan QR Code di bawah.
            </p>
        </div>

        <div class="space-y-5">
            {{-- Step 1: Scan QR Code --}}
            <div class="rounded-2xl border border-sky-100 bg-sky-50/50 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-sky-500 text-sm font-bold text-white">
                        1
                    </div>
                    <div class="text-sm font-bold text-slate-800">
                        Scan QR Code ini dengan aplikasi authenticator kamu:
                    </div>
                </div>

                {{-- QR Code Display --}}
                <div class="mt-4 flex flex-col items-center gap-4">
                    <div class="rounded-2xl bg-white p-4 ring-1 ring-sky-200 shadow-sm">
                        <img
                            src="data:image/svg+xml;base64,{{ $qrCodeSvg }}"
                            alt="QR Code untuk Two-Factor Authentication"
                            width="200"
                            height="200"
                            class="block"
                        >
                    </div>
                    <p class="text-xs text-slate-500 text-center max-w-xs">
                        Arahkan kamera di aplikasi authenticator ke QR Code di atas.
                    </p>
                </div>

                {{-- Manual key fallback (collapsible) --}}
                <div class="mt-4">
                    <button
                        type="button"
                        id="toggle-manual-key"
                        class="flex items-center gap-1.5 text-xs font-medium text-sky-600 hover:text-sky-700 transition"
                    >
                        <svg id="toggle-chevron" class="h-3.5 w-3.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                        Tidak bisa scan? Masukkan kode manual
                    </button>

                    <div id="manual-key-panel" class="mt-3 hidden">
                        <p class="text-xs text-slate-500 mb-2">Masukkan kode berikut secara manual di aplikasi authenticator:</p>
                        <div class="rounded-xl bg-white px-4 py-2.5 font-mono text-sm font-bold tracking-[0.2em] text-sky-700 ring-1 ring-sky-200 shadow-sm select-all text-center break-all">
                            {{ $secret }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: Enter Code --}}
            <div class="rounded-2xl border border-sky-100 bg-sky-50/50 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-sky-500 text-sm font-bold text-white">
                        2
                    </div>
                    <div class="text-sm font-bold text-slate-800">
                        Masukkan kode 6 digit dari aplikasi authenticator untuk mengkonfirmasi:
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.store') }}" class="mt-4">
                    @csrf

                    <div class="flex flex-col items-center gap-4 sm:flex-row">
                        <input
                            type="text"
                            name="code"
                            id="otp-code"
                            required
                            autofocus
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="000000"
                            class="w-full rounded-2xl border border-slate-200 bg-white py-3 text-center text-lg font-bold tracking-[0.3em] text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-100 sm:w-48"
                        >

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/20 transition hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-sky-100 sm:w-auto"
                        >
                            Aktifkan 2FA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('toggle-manual-key');
            const panel = document.getElementById('manual-key-panel');
            const chevron = document.getElementById('toggle-chevron');

            if (btn && panel && chevron) {
                btn.addEventListener('click', function () {
                    const isHidden = panel.classList.contains('hidden');
                    panel.classList.toggle('hidden', !isHidden);
                    chevron.style.transform = isHidden ? 'rotate(90deg)' : '';
                });
            }
        });
    </script>
</x-layouts.app>
