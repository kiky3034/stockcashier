<x-layouts.app :title="__('Admin Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Kelola sistem StockCashier dari sini.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.categories.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Categories</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola kategori produk.</p>
            </a>
            <a href="{{ route('admin.units.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Units</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola satuan produk.</p>
            </a>
            <a href="{{ route('admin.suppliers.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Suppliers</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola data supplier.</p>
            </a>
            <a href="{{ route('admin.warehouses.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Warehouses</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola lokasi stok.</p>
            </a>
            <a href="{{ route('admin.products.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Products</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola produk, harga, dan stok.</p>
            </a>
            <a href="{{ route('admin.stocks.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Stocks</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat stok produk per warehouse.</p>
            </a>

            <a href="{{ route('admin.stock-movements.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Stock Movements</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat riwayat perubahan stok.</p>
            </a>

            <a href="{{ route('admin.stock-adjustments.create') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Stock Adjustment</h2>
                <p class="mt-1 text-sm text-gray-600">Tambah atau kurangi stok manual.</p>
            </a>

            <a href="{{ route('admin.purchases.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Purchases</h2>
                <p class="mt-1 text-sm text-gray-600">Input dan lihat barang masuk supplier.</p>
            </a>
        </div>
    </div>
</x-layouts.app>