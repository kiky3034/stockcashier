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
    <div class="min-h-screen">
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-900">
                    StockCashier
                </a>

                <div class="flex items-center gap-4">
                    @auth
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ auth()->user()->roles->pluck('name')->join(', ') }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl">
            {{ $slot }}
        </main>
    </div>
</body>
</html>