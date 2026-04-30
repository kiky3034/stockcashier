<x-layouts.app :title="__('Owner Dashboard')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Owner Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Ringkasan performa bisnis hari ini.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('owner.reports.sales') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Sales Report
                </a>

                <a href="{{ route('owner.reports.profit') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Profit Report
                </a>

                <a href="{{ route('owner.reports.stock') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Stock Report
                </a>

                <a href="{{ route('owner.reports.purchases') }}"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Purchase Report
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Net Sales Today</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    Rp {{ number_format($netSalesToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Gross Profit Today</div>
                <div class="mt-2 text-2xl font-bold text-green-700">
                    Rp {{ number_format($grossProfitToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Transactions Today</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    {{ number_format($transactionCountToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Purchases Today</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    Rp {{ number_format($purchaseTotalToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Low Stock</div>
                <div class="mt-2 text-2xl font-bold text-red-700">
                    {{ number_format($lowStockCount, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Top Products Today</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Sales</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($topProducts as $product)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $product->product_name }}</div>
                                        <div class="mt-1 text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-600">
                                        {{ number_format($product->total_quantity, 2, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                        Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada penjualan hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Low Stock Items</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Stock</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($lowStocks as $stock)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">
                                            {{ $stock->product->name }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Alert level: {{ number_format($stock->product->stock_alert_level, 2, ',', '.') }}
                                            {{ $stock->product->unit?->abbreviation }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $stock->warehouse->name }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-red-700">
                                        {{ number_format($stock->quantity, 2, ',', '.') }}
                                        {{ $stock->product->unit?->abbreviation }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada stok menipis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="font-semibold text-gray-900">Recent Sales</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Invoice</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Cashier</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($recentSales as $sale)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $sale->invoice_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $sale->sold_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $sale->cashier->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ str_replace('_', ' ', ucfirst($sale->status)) }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>