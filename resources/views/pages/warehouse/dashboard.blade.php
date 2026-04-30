<x-layouts.app :title="__('Warehouse Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Warehouse Dashboard</h1>
            <p class="mt-2 text-gray-600">Kelola stok, produk, dan barang masuk.</p>
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
            <a href="{{ route('admin.purchases.index') }}"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Purchases</h2>
                <p class="mt-1 text-sm text-gray-600">Input dan lihat barang masuk supplier.</p>
            </a>
        </div>
    </div>
</x-layouts.app>