<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi 2FA - {{ config('app.name', 'StockCashier') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-cyan-50"></div>
        <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>
        <div class="absolute -right-24 bottom-16 h-80 w-80 rounded-full bg-cyan-200/50 blur-3xl"></div>

        <main class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white/80 p-6 shadow-2xl shadow-sky-900/10 backdrop-blur sm:p-8">
                    <div class="text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>

                        <h2 class="mt-5 text-2xl font-black tracking-tight text-slate-900">
                            Verifikasi Two-Factor
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Masukkan kode 6 digit dari aplikasi authenticator kamu, atau gunakan salah satu recovery code.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </div>
                                <div>{{ $errors->first() }}</div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700">
                                Kode OTP / Recovery Code
                            </label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    required
                                    autofocus
                                    autocomplete="one-time-code"
                                    inputmode="numeric"
                                    placeholder="000000"
                                    class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-center text-lg font-bold tracking-[0.3em] text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100"
                                >
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/20 transition hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-sky-100"
                        >
                            Verifikasi
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </button>
                    </form>

                    <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-sky-600 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
