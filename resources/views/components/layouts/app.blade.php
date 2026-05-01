@props(['title' => config('app.name', 'StockCashier')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'StockCashier') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .app-sidebar a.bg-gray-900 {
            background: linear-gradient(135deg, #38bdf8, #0284c7) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 20px -12px rgba(2, 132, 199, 0.8);
        }

        .app-sidebar a:not(.bg-gray-900):hover {
            background-color: #f0f9ff !important;
            color: #0369a1 !important;
        }

        .app-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .app-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .app-scrollbar::-webkit-scrollbar-thumb {
            background: #bae6fd;
            border-radius: 999px;
        }

        .app-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #7dd3fc;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    @php
        $user = auth()->user();
        $roleLabel = $user?->roles->pluck('name')->join(', ');
        $initials = collect(explode(' ', $user?->name ?? 'SC'))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->join('');
    @endphp

    <div class="min-h-screen overflow-hidden bg-gradient-to-br from-sky-50 via-slate-50 to-white">
        <div id="mobileSidebarBackdrop"
             class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

        <aside id="appSidebar"
               class="app-sidebar fixed inset-y-0 left-0 z-50 flex w-80 -translate-x-full flex-col border-r border-sky-100/80 bg-white/95 shadow-2xl shadow-sky-950/10 backdrop-blur-xl transition-transform duration-300 ease-out lg:translate-x-0 lg:shadow-none">
            <div class="flex min-h-0 flex-1 flex-col">
                <div class="border-b border-sky-100 px-5 py-5">
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 text-sm font-black text-white shadow-lg shadow-sky-500/25">
                                SC
                            </div>

                            <div>
                                <div class="text-lg font-black tracking-tight text-slate-950 group-hover:text-sky-700">
                                    StockCashier
                                </div>
                                <div class="text-xs font-medium text-slate-500">
                                    Inventory & POS System
                                </div>
                            </div>
                        </a>

                        <button type="button"
                                id="closeSidebarButton"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-sky-50 hover:text-sky-700 lg:hidden"
                                aria-label="Close navigation">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    @auth
                        <div class="mt-5 overflow-hidden rounded-2xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100">
                                    {{ strtoupper($initials) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-sm font-bold text-slate-950">
                                        {{ $user->name }}
                                    </div>

                                    <div class="mt-1 truncate text-xs font-medium text-slate-500">
                                        {{ $roleLabel ?: 'No role' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <nav class="app-scrollbar flex-1 space-y-6 overflow-y-auto px-4 py-5">
                    @if ($user?->hasRole('admin'))
                        <div>
                            <div class="px-3 text-[11px] font-black uppercase tracking-[0.18em] text-sky-500">
                                Admin
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">⌂</span>
                                        <span>Dashboard</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">👥</span>
                                        <span>Users</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.activity-logs.index') }}" :active="request()->routeIs('admin.activity-logs.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🧾</span>
                                        <span>Activity Logs</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.settings.edit') }}" :active="request()->routeIs('admin.settings.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">⚙️</span>
                                        <span>Settings</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.backups.index') }}" :active="request()->routeIs('admin.backups.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">☁️</span>
                                        <span>Backups</span>
                                    </span>
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasAnyRole(['admin', 'warehouse staff']))
                        <div>
                            <div class="px-3 text-[11px] font-black uppercase tracking-[0.18em] text-sky-500">
                                Master Data
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🏷️</span>
                                        <span>Categories</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.units.index') }}" :active="request()->routeIs('admin.units.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">📏</span>
                                        <span>Units</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.suppliers.index') }}" :active="request()->routeIs('admin.suppliers.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🚚</span>
                                        <span>Suppliers</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.warehouses.index') }}" :active="request()->routeIs('admin.warehouses.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🏬</span>
                                        <span>Warehouses</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">📦</span>
                                        <span>Products</span>
                                    </span>
                                </x-sidebar-link>
                            </div>
                        </div>

                        <div>
                            <div class="px-3 text-[11px] font-black uppercase tracking-[0.18em] text-sky-500">
                                Inventory
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.stocks.index') }}" :active="request()->routeIs('admin.stocks.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">📊</span>
                                        <span>Stocks</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.stock-movements.index') }}" :active="request()->routeIs('admin.stock-movements.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🔁</span>
                                        <span>Stock Movements</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.stock-adjustments.create') }}" :active="request()->routeIs('admin.stock-adjustments.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🧮</span>
                                        <span>Stock Adjustment</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.purchases.index') }}" :active="request()->routeIs('admin.purchases.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🛒</span>
                                        <span>Purchases</span>
                                    </span>
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasRole('cashier'))
                        <div>
                            <div class="px-3 text-[11px] font-black uppercase tracking-[0.18em] text-sky-500">
                                Cashier
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('cashier.dashboard') }}" :active="request()->routeIs('cashier.dashboard')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">⌂</span>
                                        <span>Dashboard</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('cashier.pos.index') }}" :active="request()->routeIs('cashier.pos.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🧾</span>
                                        <span>POS</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('cashier.sales.index') }}" :active="request()->routeIs('cashier.sales.*') || request()->routeIs('cashier.refunds.*')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">💳</span>
                                        <span>Sales History</span>
                                    </span>
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasRole('owner'))
                        <div>
                            <div class="px-3 text-[11px] font-black uppercase tracking-[0.18em] text-sky-500">
                                Owner Reports
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('owner.dashboard') }}" :active="request()->routeIs('owner.dashboard')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">⌂</span>
                                        <span>Dashboard</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.sales') }}" :active="request()->routeIs('owner.reports.sales')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">📈</span>
                                        <span>Sales Report</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.profit') }}" :active="request()->routeIs('owner.reports.profit')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">💰</span>
                                        <span>Profit Report</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.stock') }}" :active="request()->routeIs('owner.reports.stock')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">📦</span>
                                        <span>Stock Report</span>
                                    </span>
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.purchases') }}" :active="request()->routeIs('owner.reports.purchases')">
                                    <span class="flex items-center gap-3">
                                        <span class="text-base">🛒</span>
                                        <span>Purchase Report</span>
                                    </span>
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif
                </nav>

                @auth
                    <div class="border-t border-sky-100 p-4">
                        <form method="POST"
                              action="{{ route('logout') }}"
                              data-confirm-submit
                              data-confirm-title="Logout?"
                              data-confirm-text="Kamu akan keluar dari StockCashier."
                              data-confirm-button="Ya, logout"
                              data-confirm-icon="question">
                            @csrf

                            <button type="submit"
                                    class="group flex w-full items-center justify-center gap-2 rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3 text-sm font-bold text-sky-700 transition hover:border-sky-200 hover:bg-sky-100">
                                <span>Logout</span>
                                <span class="transition group-hover:translate-x-0.5">→</span>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-80">
            <header class="sticky top-0 z-30 border-b border-sky-100/80 bg-white/85 px-4 py-3 backdrop-blur-xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button"
                                id="openSidebarButton"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-sky-100 bg-white text-sky-700 shadow-sm hover:bg-sky-50 lg:hidden"
                                aria-label="Open navigation">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <div class="text-xs font-bold uppercase tracking-[0.16em] text-sky-500">
                                {{ config('app.name', 'StockCashier') }}
                            </div>

                            <h1 class="truncate text-lg font-black tracking-tight text-slate-950 sm:text-xl">
                                {{ $title }}
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('dashboard') }}"
                           class="hidden rounded-2xl border border-sky-100 bg-white px-4 py-2.5 text-sm font-bold text-sky-700 shadow-sm transition hover:bg-sky-50 sm:inline-flex">
                            Home
                        </a>

                        @auth
                            <div class="hidden items-center gap-3 rounded-2xl border border-sky-100 bg-white px-3 py-2 shadow-sm md:flex">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-xs font-black text-sky-700">
                                    {{ strtoupper($initials) }}
                                </div>

                                <div class="max-w-40">
                                    <div class="truncate text-sm font-bold text-slate-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="truncate text-xs text-slate-500">
                                        {{ $roleLabel ?: 'No role' }}
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="flex-1 px-3 py-4 sm:px-5 sm:py-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <x-sweet-alert />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            const openButton = document.getElementById('openSidebarButton');
            const closeButton = document.getElementById('closeSidebarButton');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                backdrop?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                backdrop?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            }

            openButton?.addEventListener('click', openSidebar);
            closeButton?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            document.querySelectorAll('#appSidebar a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });

            document.querySelectorAll('[data-confirm-submit]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmBypassed === 'true') {
                        return;
                    }

                    event.preventDefault();

                    const title = form.dataset.confirmTitle || 'Apakah kamu yakin?';
                    const text = form.dataset.confirmText || 'Aksi ini tidak bisa dibatalkan.';
                    const confirmButtonText = form.dataset.confirmButton || 'Ya, lanjutkan';
                    const icon = form.dataset.confirmIcon || 'warning';

                    if (typeof Swal === 'undefined') {
                        if (confirm(text)) {
                            form.dataset.confirmBypassed = 'true';
                            form.submit();
                        }

                        return;
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        confirmButtonColor: '#0284c7',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmBypassed = 'true';
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
