<x-layouts.app :title="__('Admin Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">
                Ringkasan operasional StockCashier hari ini.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Net Sales Today</div>
                <div class="mt-2 text-2xl font-bold text-green-700">
                    Rp {{ number_format($netSalesToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Transactions Today</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    {{ number_format($transactionsToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Purchases Today</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    Rp {{ number_format($purchasesToday, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Inventory Value</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    Rp {{ number_format($inventoryValue, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <div class="text-sm text-gray-500">Total Products</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    {{ number_format($totalProducts, 0, ',', '.') }}
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Active: {{ number_format($activeProducts, 0, ',', '.') }}
                </p>
            </a>

            <a href="{{ route('admin.stocks.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <div class="text-sm text-gray-500">Low Stock Items</div>
                <div class="mt-2 text-2xl font-bold text-red-700">
                    {{ number_format($lowStockCount, 0, ',', '.') }}
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Produk yang stoknya berada di bawah alert level.
                </p>
            </a>

            <a href="{{ route('admin.activity-logs.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <div class="text-sm text-gray-500">Audit Activity</div>
                <div class="mt-2 text-2xl font-bold text-gray-900">
                    {{ number_format($latestActivities->count(), 0, ',', '.') }}
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Aktivitas sistem terbaru.
                </p>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Top Products Today</h2>
                    <a href="{{ route('owner.reports.sales') }}"
                       class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                        View Report
                    </a>
                </div>

                @php
                    $maxTopQty = max((float) ($topProductsToday->max('total_quantity') ?? 0), 1);
                @endphp

                <div class="mt-5 space-y-4">
                    @forelse ($topProductsToday as $product)
                        @php
                            $percentage = ((float) $product->total_quantity / $maxTopQty) * 100;
                        @endphp

                        <div>
                            <div class="flex justify-between gap-3 text-sm">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $product->product_name }}</div>
                                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                </div>

                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        {{ number_format($product->total_quantity, 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 h-2 rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-gray-900" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                            Belum ada penjualan hari ini.
                        </div>
                    @endforelse
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
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Cashier</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Total</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">
                                            {{ $sale->invoice_number }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $sale->sold_at?->format('d M Y H:i') }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $sale->cashier?->name ?? '-' }}
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
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Recent Stock Movements</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Type</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Change</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($recentStockMovements as $movement)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">
                                            {{ $movement->product?->name ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $movement->warehouse?->name ?? '-' }} · {{ $movement->created_at->format('d M Y H:i') }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ str_replace('_', ' ', ucwords($movement->type, '_')) }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold {{ $movement->quantity_change >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $movement->quantity_change >= 0 ? '+' : '' }}{{ number_format($movement->quantity_change, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada stock movement.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Latest Activities</h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse ($latestActivities as $activity)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ str_replace('_', ' ', ucwords($activity->event, '_')) }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-600">
                                        {{ $activity->description ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm text-gray-500">
                            Belum ada activity log.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>