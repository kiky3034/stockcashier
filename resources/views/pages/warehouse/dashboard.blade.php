<x-layouts.app :title="__('Warehouse Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Warehouse Dashboard</h1>
            <p class="mt-2 text-gray-600">
                Kelola produk, stok, stock adjustment, dan barang masuk.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
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

            <a href="{{ route('admin.stock-adjustments.create') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Stock Adjustment</h2>
                <p class="mt-1 text-sm text-gray-600">Tambah atau kurangi stok.</p>
            </a>

            <a href="{{ route('admin.purchases.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Purchases</h2>
                <p class="mt-1 text-sm text-gray-600">Input barang masuk supplier.</p>
            </a>
        </div>
    </div>
</x-layouts.app>