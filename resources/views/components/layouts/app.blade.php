@props(['title' => config('app.name', 'StockCashier')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'StockCashier') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">
    @php
        $user = auth()->user();
    @endphp

    <div class="min-h-screen lg:flex">
        <aside class="border-b border-gray-200 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:w-72 lg:border-b-0 lg:border-r">
            <div class="flex h-full flex-col">
                <div class="border-b border-gray-200 px-6 py-5">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900">
                        StockCashier
                    </a>

                    @auth
                        <div class="mt-3 rounded-lg bg-gray-50 p-3">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                {{ $user->roles->pluck('name')->join(', ') }}
                            </div>
                        </div>
                    @endauth
                </div>

                <nav class="flex-1 space-y-6 overflow-y-auto px-4 py-5">
                    @if ($user?->hasRole('admin'))
                        <div>
                            <div class="px-3 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Admin
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                                    Dashboard
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                                    Users
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.activity-logs.index') }}" :active="request()->routeIs('admin.activity-logs.*')">
                                    Activity Logs
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.settings.edit') }}" :active="request()->routeIs('admin.settings.*')">
                                    Settings
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.backups.index') }}" :active="request()->routeIs('admin.backups.*')">
                                    Backups
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasAnyRole(['admin', 'warehouse staff']))
                        <div>
                            <div class="px-3 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Master Data
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                                    Categories
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.units.index') }}" :active="request()->routeIs('admin.units.*')">
                                    Units
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.suppliers.index') }}" :active="request()->routeIs('admin.suppliers.*')">
                                    Suppliers
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.warehouses.index') }}" :active="request()->routeIs('admin.warehouses.*')">
                                    Warehouses
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')">
                                    Products
                                </x-sidebar-link>
                            </div>
                        </div>

                        <div>
                            <div class="px-3 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Inventory
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('admin.stocks.index') }}" :active="request()->routeIs('admin.stocks.*')">
                                    Stocks
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.stock-movements.index') }}" :active="request()->routeIs('admin.stock-movements.*')">
                                    Stock Movements
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.stock-adjustments.create') }}" :active="request()->routeIs('admin.stock-adjustments.*')">
                                    Stock Adjustment
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('admin.purchases.index') }}" :active="request()->routeIs('admin.purchases.*')">
                                    Purchases
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasRole('cashier'))
                        <div>
                            <div class="px-3 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Cashier
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('cashier.dashboard') }}" :active="request()->routeIs('cashier.dashboard')">
                                    Dashboard
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('cashier.pos.index') }}" :active="request()->routeIs('cashier.pos.*')">
                                    POS
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('cashier.sales.index') }}" :active="request()->routeIs('cashier.sales.*') || request()->routeIs('cashier.refunds.*')">
                                    Sales History
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif

                    @if ($user?->hasRole('owner'))
                        <div>
                            <div class="px-3 text-xs font-bold uppercase tracking-wider text-gray-400">
                                Owner Reports
                            </div>

                            <div class="mt-2 space-y-1">
                                <x-sidebar-link href="{{ route('owner.dashboard') }}" :active="request()->routeIs('owner.dashboard')">
                                    Dashboard
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.sales') }}" :active="request()->routeIs('owner.reports.sales')">
                                    Sales Report
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.profit') }}" :active="request()->routeIs('owner.reports.profit')">
                                    Profit Report
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.stock') }}" :active="request()->routeIs('owner.reports.stock')">
                                    Stock Report
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('owner.reports.purchases') }}" :active="request()->routeIs('owner.reports.purchases')">
                                    Purchase Report
                                </x-sidebar-link>
                            </div>
                        </div>
                    @endif
                </nav>

                @auth
                    <div class="border-t border-gray-200 p-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
            <header class="border-b border-gray-200 bg-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">
                            {{ $title }}
                        </h1>
                    </div>

                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Home
                    </a>
                </div>
            </header>

            <main class="flex-1">
                <div class="mx-auto max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <x-sweet-alert />
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-confirm-submit]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const title = form.dataset.confirmTitle || 'Apakah kamu yakin?';
                    const text = form.dataset.confirmText || 'Aksi ini tidak bisa dibatalkan.';
                    const confirmButtonText = form.dataset.confirmButton || 'Ya, lanjutkan';
                    const icon = form.dataset.confirmIcon || 'warning';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        confirmButtonColor: '#111827',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>