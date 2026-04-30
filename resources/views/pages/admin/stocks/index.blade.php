<x-layouts.app :title="__('Stocks')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Stocks</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Lihat stok produk saat ini per warehouse.
                </p>
            </div>

            <a href="{{ route('admin.stock-adjustments.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Stock Adjustment
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.stocks.index') }}" class="grid gap-3 md:grid-cols-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari produk, SKU, atau barcode..."
                           class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <select name="warehouse_id"
                            class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        <option value="">All Warehouses</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.stocks.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Quantity</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($stocks as $stock)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $stock->product->name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        SKU: {{ $stock->product->sku }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $stock->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $stock->product->category?->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-right text-gray-900">
                                    {{ number_format($stock->quantity, 2, ',', '.') }}
                                    {{ $stock->product->unit?->abbreviation }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($stock->quantity <= $stock->product->stock_alert_level)
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            Safe
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>