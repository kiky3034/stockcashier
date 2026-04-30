<x-layouts.app :title="__('Stock Report')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Stock Report</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Laporan stok produk per warehouse.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('owner.reports.stock.export', request()->query()) }}"
                   class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Export CSV
                </a>

                <a href="{{ route('owner.dashboard') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Dashboard
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('owner.reports.stock') }}" class="grid gap-3 md:grid-cols-5">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari produk, SKU, barcode..."
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

                <select name="status"
                        class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                    <option value="">All Status</option>
                    <option value="low" @selected($status === 'low')>Low Stock</option>
                    <option value="safe" @selected($status === 'safe')>Safe</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Filter
                    </button>

                    <a href="{{ route('owner.reports.stock') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Quantity</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    {{ number_format($totalQuantity, 2, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Inventory Value</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Low Stock Items</div>
                <div class="mt-2 text-xl font-bold text-red-700">
                    {{ number_format($lowStockCount, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Quantity</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Alert</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Value</th>
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
                                    {{ $stock->product->category?->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $stock->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    {{ number_format($stock->quantity, 2, ',', '.') }}
                                    {{ $stock->product->unit?->abbreviation }}
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    {{ number_format($stock->product->stock_alert_level, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($stock->quantity * $stock->product->cost_price, 0, ',', '.') }}
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
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data stok.
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