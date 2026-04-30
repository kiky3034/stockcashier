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
        </div>
    </div>
</x-layouts.app>