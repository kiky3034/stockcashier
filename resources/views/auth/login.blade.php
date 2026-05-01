<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'StockCashier') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-cyan-50"></div>
        <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-sky-200/40 blur-3xl"></div>
        <div class="absolute -right-24 bottom-16 h-80 w-80 rounded-full bg-cyan-200/50 blur-3xl"></div>
        <div class="absolute left-1/2 top-1/3 h-64 w-64 -translate-x-1/2 rounded-full bg-blue-100/50 blur-3xl"></div>

        <main class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/70 bg-white/80 shadow-2xl shadow-sky-900/10 backdrop-blur xl:grid-cols-[1.05fr_0.95fr]">
                <section class="hidden bg-gradient-to-br from-sky-500 via-sky-400 to-cyan-400 p-10 text-white xl:flex xl:flex-col xl:justify-between">
                    <div>
                        <div class="inline-flex items-center gap-3 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold ring-1 ring-white/20">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-lime-300"></span>
                            Retail Inventory & POS System
                        </div>

                        <div class="mt-10">
                            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white/20 text-white ring-1 ring-white/30">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 7h12l-1 14H7L6 7Z" />
                                    <path d="M9 7a3 3 0 0 1 6 0" />
                                    <path d="M9 12h6" />
                                    <path d="M9 16h4" />
                                </svg>
                            </div>

                            <h1 class="mt-8 max-w-md text-4xl font-black tracking-tight">
                                StockCashier
                            </h1>

                            <p class="mt-4 max-w-md text-base leading-7 text-sky-50">
                                Kelola POS, stok, pembelian, refund, laporan, dan audit aktivitas dalam satu dashboard yang ringan dan mudah digunakan.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-3xl bg-white/15 p-4 ring-1 ring-white/20">
                            <div class="text-2xl font-black">POS</div>
                            <div class="mt-1 text-xs text-sky-50">Fast checkout</div>
                        </div>
                        <div class="rounded-3xl bg-white/15 p-4 ring-1 ring-white/20">
                            <div class="text-2xl font-black">Stock</div>
                            <div class="mt-1 text-xs text-sky-50">Warehouse aware</div>
                        </div>
                        <div class="rounded-3xl bg-white/15 p-4 ring-1 ring-white/20">
                            <div class="text-2xl font-black">Audit</div>
                            <div class="mt-1 text-xs text-sky-50">Activity logs</div>
                        </div>
                    </div>
                </section>

                <section class="p-6 sm:p-8 lg:p-12">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-8 text-center xl:text-left">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-50 text-sky-600 ring-1 ring-sky-100 xl:mx-0">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20" />
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />
                                </svg>
                            </div>

                            <h2 class="mt-5 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                                Welcome back
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Login ke dashboard {{ config('app.name', 'StockCashier') }} untuk melanjutkan pekerjaanmu.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
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

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700">
                                    Email
                                </label>
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16v16H4z" />
                                            <path d="m22 6-10 7L2 6" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        placeholder="you@example.com"
                                        class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700">
                                    Password
                                </label>
                                <div class="relative mt-2">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" />
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                        </svg>
                                    </span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="••••••••"
                                        class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-12 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100"
                                    >
                                    <button type="button"
                                            id="togglePasswordButton"
                                            class="absolute inset-y-0 right-3 flex items-center rounded-xl px-2 text-slate-400 transition hover:text-sky-600"
                                            aria-label="Show password">
                                        <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        value="1"
                                        class="rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                    >
                                    <span class="text-sm text-slate-600">Remember me</span>
                                </label>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/20 transition hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-sky-100"
                            >
                                Login
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </button>
                        </form>

                        <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            <div class="font-semibold text-slate-800">Role area</div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Setelah login, kamu akan otomatis diarahkan ke dashboard sesuai role: admin, owner, cashier, atau warehouse staff.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePasswordButton');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function () {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                });
            }

            @if ($errors->any())
                if (window.Toast) {
                    Toast.fire({
                        icon: 'error',
                        title: @json($errors->first())
                    });
                }
            @endif
        });
    </script>
</body>
</html>
