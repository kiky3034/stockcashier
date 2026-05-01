@php
    $netSalesTodayText = 'Rp ' . number_format($netSalesToday, 0, ',', '.');
    $grossProfitTodayText = 'Rp ' . number_format($grossProfitToday, 0, ',', '.');
    $transactionCountTodayText = number_format($transactionCountToday, 0, ',', '.');
    $purchaseTotalTodayText = 'Rp ' . number_format($purchaseTotalToday, 0, ',', '.');
    $lowStockCountText = number_format($lowStockCount, 0, ',', '.');

    $ownerDashboardSummary = [
        'netSalesToday' => $netSalesTodayText,
        'grossProfitToday' => $grossProfitTodayText,
        'transactionCountToday' => $transactionCountTodayText,
        'purchaseTotalToday' => $purchaseTotalTodayText,
        'lowStockCount' => $lowStockCountText,
    ];

    $ownerLowStockToastMessage = 'Ada ' . $lowStockCountText . ' item stok menipis.';
@endphp

<x-layouts.app :title="__('Owner Dashboard')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Owner Dashboard"
            description="Ringkasan performa bisnis hari ini, tren 7 hari, produk terlaris, dan stok menipis."
        >
            <x-slot:actions>
                <x-ui.button-secondary id="ownerDashboardSummaryButton" type="button">
                    Summary
                </x-ui.button-secondary>

                <x-ui.button-secondary id="copyOwnerDashboardSummaryButton" type="button">
                    Copy Summary
                </x-ui.button-secondary>

                <x-ui.link-button href="{{ route('owner.reports.sales') }}" variant="secondary">
                    Sales
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('owner.reports.profit') }}" variant="secondary">
                    Profit
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('owner.reports.stock') }}" variant="secondary">
                    Stock
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('owner.reports.purchases') }}">
                    Purchases
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-ui.stat-card
                label="Net Sales Today"
                :value="$netSalesTodayText"
                description="Penjualan bersih setelah refund"
                tone="sky"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18" />
                        <path d="m7 15 4-4 3 3 5-7" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Gross Profit Today"
                :value="$grossProfitTodayText"
                description="Estimasi profit kotor hari ini"
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
                :value="$transactionCountTodayText"
                description="Jumlah transaksi valid"
                tone="slate"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 2v4" />
                        <path d="M16 2v4" />
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M8 11h8" />
                        <path d="M8 15h5" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Purchases Today"
                :value="$purchaseTotalTodayText"
                description="Nilai pembelian supplier"
                tone="amber"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>

            <x-ui.stat-card
                label="Low Stock"
                :value="$lowStockCountText"
                description="Produk perlu restock"
                tone="red"
            >
                <x-slot:icon>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                </x-slot:icon>
            </x-ui.stat-card>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Sales Last 7 Days</h2>
                        <p class="mt-1 text-sm text-slate-500">Net sales setelah refund.</p>
                    </div>

                    <a href="{{ route('owner.reports.sales') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700">
                        Detail
                    </a>
                </div>

                <div class="mt-6 flex h-56 items-end gap-2 sm:gap-3">
                    @foreach ($dailyPerformance as $day)
                        @php
                            $height = max(((float) $day['sales'] / $maxDailySales) * 100, 4);
                        @endphp

                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <div class="max-w-full truncate text-[11px] font-semibold text-slate-600">
                                {{ number_format($day['sales'] / 1000, 0, ',', '.') }}k
                            </div>

                            <div class="relative w-full overflow-hidden rounded-t-xl bg-sky-100">
                                <div class="w-full rounded-t-xl bg-gradient-to-t from-sky-600 to-cyan-400 transition-all" style="height: {{ $height }}%"></div>
                            </div>

                            <div class="text-[11px] text-slate-500">
                                {{ $day['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Profit Last 7 Days</h2>
                        <p class="mt-1 text-sm text-slate-500">Gross profit setelah refund.</p>
                    </div>

                    <a href="{{ route('owner.reports.profit') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700">
                        Detail
                    </a>
                </div>

                <div class="mt-6 flex h-56 items-end gap-2 sm:gap-3">
                    @foreach ($dailyPerformance as $day)
                        @php
                            $height = max(((float) $day['profit'] / $maxDailyProfit) * 100, 4);
                        @endphp

                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <div class="max-w-full truncate text-[11px] font-semibold text-emerald-700">
                                {{ number_format($day['profit'] / 1000, 0, ',', '.') }}k
                            </div>

                            <div class="relative w-full overflow-hidden rounded-t-xl bg-emerald-100">
                                <div class="w-full rounded-t-xl bg-gradient-to-t from-emerald-600 to-teal-400 transition-all" style="height: {{ $height }}%"></div>
                            </div>

                            <div class="text-[11px] text-slate-500">
                                {{ $day['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('owner.reports.sales') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900 group-hover:text-sky-700">Sales Report</h2>
                        <p class="mt-1 text-sm text-slate-500">Lihat laporan penjualan.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.profit') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600 ring-1 ring-emerald-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900 group-hover:text-sky-700">Profit Report</h2>
                        <p class="mt-1 text-sm text-slate-500">Lihat laporan profit.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.stock') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-amber-50 p-3 text-amber-600 ring-1 ring-amber-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900 group-hover:text-sky-700">Stock Report</h2>
                        <p class="mt-1 text-sm text-slate-500">Pantau stok dan inventory.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('owner.reports.purchases') }}" class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-violet-50 p-3 text-violet-600 ring-1 ring-violet-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900 group-hover:text-sky-700">Purchase Report</h2>
                        <p class="mt-1 text-sm text-slate-500">Lihat pembelian supplier.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50/60 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Top Products Today</h2>
                    <p class="mt-1 text-sm text-slate-500">Produk dengan penjualan tertinggi hari ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Product</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Qty</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Sales</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($topProducts as $product)
                                <tr class="hover:bg-sky-50/40">
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-900">{{ $product->product_name }}</div>
                                        <div class="mt-1 text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                                    </td>

                                    <td class="px-5 py-4 text-right text-slate-600">
                                        {{ number_format($product->total_quantity, 2, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                        Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center text-slate-500">
                                        Belum ada penjualan hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50/60 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Low Stock Items</h2>
                    <p class="mt-1 text-sm text-slate-500">Item yang sudah menyentuh alert level.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Product</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Warehouse</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Stock</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($lowStocks as $stock)
                                <tr class="hover:bg-red-50/40">
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-900">{{ $stock->product->name }}</div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            Alert level: {{ number_format($stock->product->stock_alert_level, 2, ',', '.') }}
                                            {{ $stock->product->unit?->abbreviation }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-600">
                                        {{ $stock->warehouse->name }}
                                    </td>

                                    <td class="px-5 py-4 text-right font-semibold text-red-700">
                                        {{ number_format($stock->quantity, 2, ',', '.') }}
                                        {{ $stock->product->unit?->abbreviation }}
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <button type="button"
                                                class="owner-low-stock-detail rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:border-red-200 hover:bg-red-50 hover:text-red-700"
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
                                    <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                        Tidak ada stok menipis.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50/60 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Recent Sales</h2>
                <p class="mt-1 text-sm text-slate-500">Transaksi terbaru yang masuk ke sistem.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Invoice</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Date</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Cashier</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600">Total</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($recentSales as $sale)
                            <tr class="hover:bg-sky-50/40">
                                <td class="px-5 py-4 font-medium text-slate-900">
                                    {{ $sale->invoice_number }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $sale->sold_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $sale->cashier->name }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        {{ str_replace('_', ' ', ucfirst($sale->status)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                class="owner-sale-copy rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-invoice="{{ e($sale->invoice_number) }}">
                                            Copy
                                        </button>

                                        <button type="button"
                                                class="owner-sale-detail rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
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
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

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
                    confirmButtonColor: '#0284c7'
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
                        confirmButtonColor: '#0284c7'
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
                        confirmButtonColor: '#0284c7'
                    });
                });
            });
        });
    </script>
</x-layouts.app>
