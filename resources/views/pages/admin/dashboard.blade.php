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
                    <a href="{{ route('admin.products.index') }}"
                       class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                        View Products
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
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
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

                                    <td class="px-4 py-3 text-right">
                                        <button type="button"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-stock-movement-detail
                                                data-product="{{ e($movement->product?->name ?? '-') }}"
                                                data-warehouse="{{ e($movement->warehouse?->name ?? '-') }}"
                                                data-user="{{ e($movement->user?->name ?? '-') }}"
                                                data-type="{{ e(str_replace('_', ' ', ucwords($movement->type, '_'))) }}"
                                                data-before="{{ e(number_format($movement->quantity_before, 2, ',', '.')) }}"
                                                data-change="{{ e(($movement->quantity_change >= 0 ? '+' : '') . number_format($movement->quantity_change, 2, ',', '.')) }}"
                                                data-after="{{ e(number_format($movement->quantity_after, 2, ',', '.')) }}"
                                                data-notes="{{ e($movement->notes ?? '-') }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
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

                                <button type="button"
                                        class="shrink-0 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                        data-activity-detail
                                        data-event="{{ e(str_replace('_', ' ', ucwords($activity->event, '_'))) }}"
                                        data-description="{{ e($activity->description ?? '-') }}"
                                        data-user="{{ e($activity->user?->name ?? 'System') }}"
                                        data-date="{{ e($activity->created_at->format('d M Y H:i')) }}"
                                        data-properties="{{ base64_encode(json_encode($activity->properties ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) }}">
                                    Detail
                                </button>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toast = window.Toast || null;
                const swal = window.Swal || null;

                function notify(options) {
                    if (toast) {
                        toast.fire(options);
                        return;
                    }

                    if (swal) {
                        swal.fire({
                            icon: options.icon || 'info',
                            title: options.title || '',
                            text: options.text || '',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                }

                const lowStockCount = @json((int) $lowStockCount);

                if (lowStockCount > 0) {
                    notify({
                        icon: 'warning',
                        title: `${lowStockCount} item low stock perlu dicek`
                    });
                }

                document.querySelectorAll('[data-stock-movement-detail]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        const html = `
                            <div class="space-y-2 text-left text-sm">
                                <div><strong>Product:</strong> ${button.dataset.product}</div>
                                <div><strong>Warehouse:</strong> ${button.dataset.warehouse}</div>
                                <div><strong>User:</strong> ${button.dataset.user}</div>
                                <div><strong>Type:</strong> ${button.dataset.type}</div>
                                <hr>
                                <div><strong>Before:</strong> ${button.dataset.before}</div>
                                <div><strong>Change:</strong> ${button.dataset.change}</div>
                                <div><strong>After:</strong> ${button.dataset.after}</div>
                                <hr>
                                <div><strong>Notes:</strong> ${button.dataset.notes}</div>
                            </div>
                        `;

                        if (swal) {
                            swal.fire({
                                icon: 'info',
                                title: 'Stock Movement Detail',
                                html: html,
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#111827'
                            });
                        }
                    });
                });

                document.querySelectorAll('[data-activity-detail]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        let properties = {};

                        try {
                            properties = JSON.parse(atob(button.dataset.properties || 'e30='));
                        } catch (error) {
                            properties = {};
                        }

                        const propertiesHtml = Object.keys(properties).length
                            ? `<pre class="mt-3 max-h-72 overflow-auto rounded-lg bg-gray-100 p-3 text-left text-xs">${JSON.stringify(properties, null, 2)}</pre>`
                            : `<div class="mt-3 rounded-lg bg-gray-50 p-3 text-sm text-gray-500">Tidak ada properties tambahan.</div>`;

                        if (swal) {
                            swal.fire({
                                icon: 'info',
                                title: button.dataset.event,
                                html: `
                                    <div class="space-y-2 text-left text-sm">
                                        <div><strong>Description:</strong> ${button.dataset.description}</div>
                                        <div><strong>User:</strong> ${button.dataset.user}</div>
                                        <div><strong>Date:</strong> ${button.dataset.date}</div>
                                        ${propertiesHtml}
                                    </div>
                                `,
                                width: 700,
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#111827'
                            });
                        }
                    });
                });
            });
        </script>

</x-layouts.app>