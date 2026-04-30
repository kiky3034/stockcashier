<x-layouts.app :title="__('Products')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola produk, harga, dan stok awal.
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Add Product
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari produk, SKU, atau barcode..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.products.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Price</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Stock</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($products as $product)
                            @php
                                $totalStock = $product->stocks->sum('quantity');
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $product->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        SKU: {{ $product->sku }}
                                        @if ($product->barcode)
                                            · Barcode: {{ $product->barcode }}
                                        @endif
                                    </div>

                                    @if ($product->supplier)
                                        <div class="mt-1 text-xs text-gray-500">
                                            Supplier: {{ $product->supplier->name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $product->category?->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    <div>Buy: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</div>
                                    <div>Sell: Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    @if ($product->track_stock)
                                        <span class="{{ $totalStock <= $product->stock_alert_level ? 'text-red-600 font-semibold' : '' }}">
                                            {{ number_format($totalStock, 2, ',', '.') }}
                                            {{ $product->unit?->abbreviation }}
                                        </span>

                                        @if ($totalStock <= $product->stock_alert_level)
                                            <div class="mt-1 text-xs text-red-600">
                                                Low stock
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Not tracked</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($product->is_active)
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.products.destroy', $product) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus product ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada product.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>