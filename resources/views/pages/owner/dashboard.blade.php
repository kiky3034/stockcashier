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
                <button type="button"
                        id="ownerDashboardSummaryButton"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Summary
                </button>

                <button type="button"
                        id="copyOwnerDashboardSummaryButton"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Copy Summary
                </button>

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

        {{-- SUMMARY CARDS --}}
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

        {{-- 📊 CHART SECTION: Sales & Profit Last 7 Days --}}
        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">Sales Last 7 Days</h2>
                        <p class="mt-1 text-sm text-gray-500">Net sales setelah refund.</p>
                    </div>

                    <a href="{{ route('owner.reports.sales') }}"
                       class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                        Detail
                    </a>
                </div>

                <div class="mt-6 flex h-56 items-end gap-3">
                    @foreach ($dailyPerformance as $day)
                        @php
                            $height = max(((float) $day['sales'] / $maxDailySales) * 100, 4);
                        @endphp

                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <div class="text-xs font-semibold text-gray-700">
                                {{ number_format($day['sales'] / 1000, 0, ',', '.') }}k
                            </div>

                            <div class="w-full rounded-t-lg bg-gray-900" style="height: {{ $height }}%"></div>

                            <div class="text-xs text-gray-500">
                                {{ $day['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">Profit Last 7 Days</h2>
                        <p class="mt-1 text-sm text-gray-500">Gross profit setelah refund.</p>
                    </div>

                    <a href="{{ route('owner.reports.profit') }}"
                       class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                        Detail
                    </a>
                </div>

                <div class="mt-6 flex h-56 items-end gap-3">
                    @foreach ($dailyPerformance as $day)
                        @php
                            $height = max(((float) $day['profit'] / $maxDailyProfit) * 100, 4);
                        @endphp

                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <div class="text-xs font-semibold text-green-700">
                                {{ number_format($day['profit'] / 1000, 0, ',', '.') }}k
                            </div>

                            <div class="w-full rounded-t-lg bg-green-700" style="height: {{ $height }}%"></div>

                            <div class="text-xs text-gray-500">
                                {{ $day['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 🚀 SHORTCUT REPORT CARDS --}}
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('owner.reports.sales') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Sales Report</h2>
                        <p class="mt-1 text-sm text-gray-600">Lihat laporan penjualan.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.profit') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Profit Report</h2>
                        <p class="mt-1 text-sm text-gray-600">Lihat laporan profit.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.stock') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-orange-100 p-3">
                        <svg class="h-6 w-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Stock Report</h2>
                        <p class="mt-1 text-sm text-gray-600">Pantau stok dan nilai inventory.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.purchases') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-purple-100 p-3">
                        <svg class="h-6 w-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Purchase Report</h2>
                        <p class="mt-1 text-sm text-gray-600">Lihat pembelian supplier.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- TOP PRODUCTS & LOW STOCK --}}
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
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
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

                                    <td class="px-4 py-3 text-right">
                                        <button type="button"
                                                class="owner-low-stock-detail rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-product="{{ e($stock->product->name) }}"
                                                data-sku="{{ e($stock->product->sku) }}"
                                                data-warehouse="{{ e($stock->warehouse->name) }}"
                                                data-quantity="{{ number_format($stock->quantity, 2, ',', '.') }}"
                                                data-unit="{{ e($stock->product->unit?->abbreviation ?? '') }}"
                                                data-alert="{{ number_format($stock->product->stock_alert_level, 2, ',', '.') }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada stok menipis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RECENT SALES --}}
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
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
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

                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                class="owner-sale-copy rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-invoice="{{ e($sale->invoice_number) }}">
                                            Copy
                                        </button>

                                        <button type="button"
                                                class="owner-sale-detail rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-invoice="{{ e($sale->invoice_number) }}"
                                                data-date="{{ e($sale->sold_at?->format('d M Y H:i') ?? '-') }}"
                                                data-cashier="{{ e($sale->cashier->name) }}"
                                                data-status="{{ e(str_replace('_', ' ', ucfirst($sale->status))) }}"
                                                data-total="Rp {{ number_format($sale->total_amount, 0, ',', '.') }}">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@php
    $ownerDashboardSummary = [
        'netSalesToday' => 'Rp ' . number_format($netSalesToday, 0, ',', '.'),
        'grossProfitToday' => 'Rp ' . number_format($grossProfitToday, 0, ',', '.'),
        'transactionCountToday' => number_format($transactionCountToday, 0, ',', '.'),
        'purchaseTotalToday' => 'Rp ' . number_format($purchaseTotalToday, 0, ',', '.'),
        'lowStockCount' => number_format($lowStockCount, 0, ',', '.'),
    ];

    $ownerLowStockToastMessage = 'Ada ' . number_format($lowStockCount, 0, ',', '.') . ' item stok menipis.';
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const summary = {{ Illuminate\Support\Js::from($ownerDashboardSummary) }};

        function showToast(icon, title) {
            if (window.Toast) {
                Toast.fire({ icon, title });
                return;
            }

            if (window.Swal) {
                Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
            }
        }

        function copyText(text, successMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function () {
                    showToast('success', successMessage);
                });
                return;
            }

            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            document.execCommand('copy');
            textarea.remove();
            showToast('success', successMessage);
        }

        @if ($lowStockCount > 0)
            showToast('warning', {{ Illuminate\Support\Js::from($ownerLowStockToastMessage) }});
        @endif

        document.getElementById('ownerDashboardSummaryButton')?.addEventListener('click', function () {
            if (!window.Swal) {
                return;
            }

            Swal.fire({
                icon: 'info',
                title: 'Owner Dashboard Summary',
                html: `
                    <div class="text-left text-sm">
                        <div class="flex justify-between gap-6 border-b py-2"><span>Net Sales Today</span><strong>${summary.netSalesToday}</strong></div>
                        <div class="flex justify-between gap-6 border-b py-2"><span>Gross Profit Today</span><strong>${summary.grossProfitToday}</strong></div>
                        <div class="flex justify-between gap-6 border-b py-2"><span>Transactions Today</span><strong>${summary.transactionCountToday}</strong></div>
                        <div class="flex justify-between gap-6 border-b py-2"><span>Purchases Today</span><strong>${summary.purchaseTotalToday}</strong></div>
                        <div class="flex justify-between gap-6 py-2"><span>Low Stock</span><strong>${summary.lowStockCount}</strong></div>
                    </div>
                `,
                confirmButtonText: 'OK',
                confirmButtonColor: '#111827'
            });
        });

        document.getElementById('copyOwnerDashboardSummaryButton')?.addEventListener('click', function () {
            const text = [
                'Owner Dashboard Summary',
                `Net Sales Today: ${summary.netSalesToday}`,
                `Gross Profit Today: ${summary.grossProfitToday}`,
                `Transactions Today: ${summary.transactionCountToday}`,
                `Purchases Today: ${summary.purchaseTotalToday}`,
                `Low Stock: ${summary.lowStockCount}`,
            ].join('\n');

            copyText(text, 'Summary dashboard berhasil disalin.');
        });

        document.querySelectorAll('.owner-low-stock-detail').forEach(function (button) {
            button.addEventListener('click', function () {
                if (!window.Swal) {
                    return;
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Low Stock Detail',
                    html: `
                        <div class="text-left text-sm">
                            <div class="flex justify-between gap-6 border-b py-2"><span>Product</span><strong>${button.dataset.product}</strong></div>
                            <div class="flex justify-between gap-6 border-b py-2"><span>SKU</span><strong>${button.dataset.sku}</strong></div>
                            <div class="flex justify-between gap-6 border-b py-2"><span>Warehouse</span><strong>${button.dataset.warehouse}</strong></div>
                            <div class="flex justify-between gap-6 border-b py-2"><span>Current Stock</span><strong>${button.dataset.quantity} ${button.dataset.unit}</strong></div>
                            <div class="flex justify-between gap-6 py-2"><span>Alert Level</span><strong>${button.dataset.alert} ${button.dataset.unit}</strong></div>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#111827'
                });
            });
        });

        document.querySelectorAll('.owner-sale-copy').forEach(function (button) {
            button.addEventListener('click', function () {
                copyText(button.dataset.invoice, 'Invoice berhasil disalin.');
            });
        });

        document.querySelectorAll('.owner-sale-detail').forEach(function (button) {
            button.addEventListener('click', function () {
                if (!window.Swal) {
                    return;
                }

                Swal.fire({
                    icon: 'info',
                    title: button.dataset.invoice,
                    html: `
                        <div class="text-left text-sm">
                            <div class="flex justify-between gap-6 border-b py-2"><span>Date</span><strong>${button.dataset.date}</strong></div>
                            <div class="flex justify-between gap-6 border-b py-2"><span>Cashier</span><strong>${button.dataset.cashier}</strong></div>
                            <div class="flex justify-between gap-6 border-b py-2"><span>Status</span><strong>${button.dataset.status}</strong></div>
                            <div class="flex justify-between gap-6 py-2"><span>Total</span><strong>${button.dataset.total}</strong></div>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#111827'
                });
            });
        });
    });
</script>

</x-layouts.app>