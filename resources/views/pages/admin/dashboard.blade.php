<x-layouts.app :title="__('Admin Dashboard')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Admin Dashboard"
            description="Ringkasan operasional StockCashier hari ini. Pantau penjualan, stok, pembelian, dan aktivitas terbaru dari satu tempat."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.products.index') }}" variant="secondary">
                    Products
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('admin.activity-logs.index') }}">
                    Activity Logs
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-ui.stat-card
                label="Net Sales Today"
                value="Rp {{ number_format($netSalesToday, 0, ',', '.') }}"
                description="Penjualan bersih setelah refund hari ini"
                tone="green"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Transactions Today"
                value="{{ number_format($transactionsToday, 0, ',', '.') }}"
                description="Jumlah transaksi aktif hari ini"
                tone="sky"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11h6" />
                        <path d="M9 15h6" />
                        <path d="M5 3h14v18l-2-1-2 1-2-1-2 1-2-1-2 1-2-1V3Z" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Purchases Today"
                value="Rp {{ number_format($purchasesToday, 0, ',', '.') }}"
                description="Total barang masuk dari supplier"
                tone="amber"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        <path d="m3.3 7 8.7 5 8.7-5" />
                        <path d="M12 22V12" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Inventory Value"
                value="Rp {{ number_format($inventoryValue, 0, ',', '.') }}"
                description="Estimasi nilai stok berdasarkan cost price"
                tone="slate"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 7h18" />
                        <path d="M5 7v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7" />
                        <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('admin.products.index') }}"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Total Products</div>
                        <div class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalProducts, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Active: {{ number_format($activeProducts, 0, ',', '.') }} products
                        </p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100 transition group-hover:bg-sky-500 group-hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 7 12 3 4 7l8 4 8-4Z" />
                            <path d="M4 7v10l8 4 8-4V7" />
                            <path d="M12 11v10" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.stocks.index') }}"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Low Stock Items</div>
                        <div class="mt-2 text-2xl font-bold tracking-tight text-red-700">
                            {{ number_format($lowStockCount, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Produk yang stoknya berada di bawah alert level.
                        </p>
                    </div>
                    <div class="rounded-2xl bg-red-50 p-3 text-red-600 ring-1 ring-red-100 transition group-hover:bg-red-600 group-hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.activity-logs.index') }}"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Audit Activity</div>
                        <div class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                            {{ number_format($latestActivities->count(), 0, ',', '.') }}
                        </div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Aktivitas sistem terbaru.
                        </p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100 transition group-hover:bg-sky-500 group-hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-slate-900">Top Products Today</h2>
                        <p class="mt-1 text-sm text-slate-500">Produk terlaris berdasarkan quantity hari ini.</p>
                    </div>
                    <x-ui.link-button href="{{ route('admin.products.index') }}" variant="ghost" class="px-3 py-2">
                        View Products
                    </x-ui.link-button>
                </div>

                @php
                    $maxTopQty = max((float) ($topProductsToday->max('total_quantity') ?? 0), 1);
                @endphp

                <div class="mt-5 space-y-4">
                    @forelse ($topProductsToday as $product)
                        @php
                            $percentage = ((float) $product->total_quantity / $maxTopQty) * 100;
                        @endphp

                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                            <div class="flex justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-slate-900">{{ $product->product_name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                                </div>

                                <div class="shrink-0 text-right">
                                    <div class="font-semibold text-slate-900">
                                        {{ number_format($product->total_quantity, 2, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 h-2.5 rounded-full bg-white ring-1 ring-slate-100">
                                <div class="h-2.5 rounded-full bg-gradient-to-r from-sky-400 to-cyan-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-500">
                            Belum ada penjualan hari ini.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-sky-50 to-white p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="font-semibold text-slate-900">Recent Sales</h2>
                            <p class="mt-1 text-sm text-slate-500">Transaksi terbaru dari kasir.</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Invoice</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Cashier</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Total</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentSales as $sale)
                                <tr class="transition hover:bg-sky-50/50">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $sale->invoice_number }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $sale->sold_at?->format('d M Y H:i') }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $sale->cashier?->name ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4">
                                        @php
                                            $statusLabel = str_replace('_', ' ', ucfirst($sale->status));
                                        @endphp
                                        <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-sky-50 to-white p-5">
                    <h2 class="font-semibold text-slate-900">Recent Stock Movements</h2>
                    <p class="mt-1 text-sm text-slate-500">Aktivitas stok terakhir di warehouse.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Product</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Type</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Change</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentStockMovements as $movement)
                                @php
                                    $movementType = str_replace('_', ' ', ucwords($movement->type, '_'));
                                    $movementChange = ($movement->quantity_change >= 0 ? '+' : '') . number_format($movement->quantity_change, 2, ',', '.');
                                @endphp
                                <tr class="transition hover:bg-sky-50/50">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $movement->product?->name ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $movement->warehouse?->name ?? '-' }} · {{ $movement->created_at->format('d M Y H:i') }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $movementType }}
                                    </td>

                                    <td class="px-5 py-4 text-right font-semibold {{ $movement->quantity_change >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $movementChange }}
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <button type="button"
                                                class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-stock-movement-detail
                                                data-product="{{ e($movement->product?->name ?? '-') }}"
                                                data-warehouse="{{ e($movement->warehouse?->name ?? '-') }}"
                                                data-user="{{ e($movement->user?->name ?? '-') }}"
                                                data-type="{{ e($movementType) }}"
                                                data-before="{{ e(number_format($movement->quantity_before, 2, ',', '.')) }}"
                                                data-change="{{ e($movementChange) }}"
                                                data-after="{{ e(number_format($movement->quantity_after, 2, ',', '.')) }}"
                                                data-notes="{{ e($movement->notes ?? '-') }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                        Belum ada stock movement.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-sky-50 to-white p-5">
                    <h2 class="font-semibold text-slate-900">Latest Activities</h2>
                    <p class="mt-1 text-sm text-slate-500">Audit trail terbaru dari sistem.</p>
                </div>

                <div class="divide-y divide-slate-100 bg-white">
                    @forelse ($latestActivities as $activity)
                        @php
                            $activityEvent = str_replace('_', ' ', ucwords($activity->event, '_'));
                            $activityProperties = $activity->properties
                                ? json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                                : null;
                        @endphp
                        <div class="p-5 transition hover:bg-sky-50/50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900">
                                        {{ $activityEvent }}
                                    </div>

                                    <div class="mt-1 text-sm text-slate-600">
                                        {{ $activity->description ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>

                                <button type="button"
                                        class="shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                        data-activity-detail
                                        data-event="{{ e($activityEvent) }}"
                                        data-user="{{ e($activity->user?->name ?? 'System') }}"
                                        data-date="{{ e($activity->created_at->format('d M Y H:i')) }}"
                                        data-description="{{ e($activity->description ?? '-') }}"
                                        data-properties="{{ e($activityProperties ?? '-') }}">
                                    Detail
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-sm text-slate-500">
                            Belum ada activity log.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toast = window.Toast || null;
            const swal = window.Swal || null;

            @if ($lowStockCount > 0)
                if (toast) {
                    toast.fire({
                        icon: 'warning',
                        title: '{{ $lowStockCount }} item stok menipis perlu dicek.'
                    });
                }
            @endif

            document.querySelectorAll('[data-stock-movement-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!swal) {
                        return;
                    }

                    swal.fire({
                        title: 'Stock Movement Detail',
                        html: `
                            <div class="text-left text-sm">
                                <div class="mb-3 rounded-xl bg-sky-50 p-3">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-sky-700">Product</div>
                                    <div class="mt-1 font-semibold text-slate-900">${button.dataset.product}</div>
                                </div>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <div><strong>Warehouse:</strong><br>${button.dataset.warehouse}</div>
                                    <div><strong>User:</strong><br>${button.dataset.user}</div>
                                    <div><strong>Type:</strong><br>${button.dataset.type}</div>
                                    <div><strong>Change:</strong><br>${button.dataset.change}</div>
                                    <div><strong>Before:</strong><br>${button.dataset.before}</div>
                                    <div><strong>After:</strong><br>${button.dataset.after}</div>
                                </div>
                                <div class="mt-3"><strong>Notes:</strong><br>${button.dataset.notes}</div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9',
                        width: 560
                    });
                });
            });

            document.querySelectorAll('[data-activity-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (!swal) {
                        return;
                    }

                    swal.fire({
                        title: button.dataset.event,
                        html: `
                            <div class="text-left text-sm">
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <div><strong>User:</strong><br>${button.dataset.user}</div>
                                    <div><strong>Date:</strong><br>${button.dataset.date}</div>
                                </div>
                                <div class="mt-3"><strong>Description:</strong><br>${button.dataset.description}</div>
                                <div class="mt-3">
                                    <strong>Properties:</strong>
                                    <pre class="mt-2 max-h-64 overflow-auto rounded-xl bg-slate-100 p-3 text-xs text-slate-700">${button.dataset.properties}</pre>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9',
                        width: 640
                    });
                });
            });
        });
    </script>
</x-layouts.app>
