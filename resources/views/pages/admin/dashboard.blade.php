<x-layouts.app :title="__('Admin Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">
                Kelola user, master data, stok, transaksi, dan audit sistem.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.users.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Users</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola user dan role.</p>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Products</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola produk dan harga.</p>
            </a>

            <a href="{{ route('admin.stocks.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Stocks</h2>
                <p class="mt-1 text-sm text-gray-600">Pantau stok per warehouse.</p>
            </a>

            <a href="{{ route('admin.activity-logs.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Activity Logs</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat audit aktivitas.</p>
            </a>
        </div>
    </div>
</x-layouts.app>