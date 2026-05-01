<x-layouts.app :title="__('Stocks')">
    @php
        $stockCollection = $stocks->getCollection();

        $lowStockCountOnPage = $stockCollection
            ->filter(fn ($stock) => (float) $stock->quantity <= (float) $stock->product->stock_alert_level)
            ->count();

        $safeStockCountOnPage = $stockCollection->count() - $lowStockCountOnPage;
        $totalQuantityOnPage = $stockCollection->sum(fn ($stock) => (float) $stock->quantity);
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Stocks"
            description="Pantau stok produk per warehouse dan identifikasi item yang mulai menipis."
        >
            <x-slot:actions>
                {{-- Kosongkan actions karena tombol sudah dipindahkan --}}
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Rows on Page"
                value="{{ number_format($stockCollection->count(), 0, ',', '.') }}"
                description="Data stok pada halaman ini"
                tone="sky"
            />

            <x-ui.stat-card
                label="Low Stock"
                value="{{ number_format($lowStockCountOnPage, 0, ',', '.') }}"
                description="Item berada di bawah alert level"
                tone="{{ $lowStockCountOnPage > 0 ? 'red' : 'green' }}"
            />

            <x-ui.stat-card
                label="Total Quantity"
                value="{{ number_format($totalQuantityOnPage, 2, ',', '.') }}"
                description="Akumulasi quantity pada halaman ini"
                tone="slate"
            />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.stocks.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari produk, SKU, atau barcode..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div class="w-full lg:w-64">
                        <select name="warehouse_id"
                                class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            <option value="">All Warehouses</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Filter
                        </button>

                        <a href="{{ route('admin.stocks.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Reset
                        </a>

                        {{-- Tombol Stock Adjustment di sini --}}
                        <a href="{{ route('admin.stock-adjustments.create') }}"
                           class="inline-flex items-center justify-center gap-1 rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Stock Adjustment
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Product</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Warehouse</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Category</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-slate-700">Quantity</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Status</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($stocks as $stock)
                            @php
                                $isLowStock = (float) $stock->quantity <= (float) $stock->product->stock_alert_level;
                                $stockDetail = [
                                    'product' => $stock->product->name,
                                    'sku' => $stock->product->sku,
                                    'barcode' => $stock->product->barcode ?: '-',
                                    'warehouse' => $stock->warehouse->name,
                                    'category' => $stock->product->category?->name ?? '-',
                                    'quantity' => number_format($stock->quantity, 2, ',', '.'),
                                    'unit' => $stock->product->unit?->abbreviation ?? '',
                                    'alert_level' => number_format($stock->product->stock_alert_level, 2, ',', '.'),
                                    'status' => $isLowStock ? 'Low Stock' : 'Safe',
                                ];
                            @endphp

                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-3 py-3 align-top">
                                    <div class="font-semibold text-slate-900 text-sm">
                                        {{ $stock->product->name }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-slate-500">
                                        <span>SKU: <span class="font-mono text-slate-700">{{ $stock->product->sku }}</span></span>
                                        @if ($stock->product->barcode)
                                            <span class="ml-2">Barcode: <span class="font-mono text-slate-700">{{ $stock->product->barcode }}</span></span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-top text-xs text-slate-600">
                                    {{ $stock->warehouse->name }}
                                </td>

                                <td class="px-3 py-3 align-top text-xs text-slate-600">
                                    {{ $stock->product->category?->name ?? '-' }}
                                </td>

                                <td class="px-3 py-3 align-top text-right font-semibold text-slate-900 text-sm">
                                    {{ number_format($stock->quantity, 2, ',', '.') }}
                                    <span class="text-xs font-medium text-slate-500">{{ $stock->product->unit?->abbreviation }}</span>
                                </td>

                                <td class="px-3 py-3 align-top">
                                    @if ($isLowStock)
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Safe
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 align-top text-right">
                                    <button type="button"
                                            class="stock-detail-button inline-flex h-8 w-8 items-center justify-center rounded-xl bg-sky-500 text-white shadow-sm transition hover:bg-sky-600"
                                            title="Detail Stock"
                                            data-stock-detail="{{ base64_encode(json_encode($stockDetail)) }}">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-10 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-3 font-semibold text-slate-900">Belum ada data stok</h3>
                                        <p class="mt-1 text-sm text-slate-500">Data stok akan muncul setelah produk dan warehouse memiliki stok.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($stocks as $stock)
                    @php
                        $isLowStock = (float) $stock->quantity <= (float) $stock->product->stock_alert_level;
                        $stockDetail = [
                            'product' => $stock->product->name,
                            'sku' => $stock->product->sku,
                            'barcode' => $stock->product->barcode ?: '-',
                            'warehouse' => $stock->warehouse->name,
                            'category' => $stock->product->category?->name ?? '-',
                            'quantity' => number_format($stock->quantity, 2, ',', '.'),
                            'unit' => $stock->product->unit?->abbreviation ?? '',
                            'alert_level' => number_format($stock->product->stock_alert_level, 2, ',', '.'),
                            'status' => $isLowStock ? 'Low Stock' : 'Safe',
                        ];
                    @endphp

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900 text-sm">{{ $stock->product->name }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">SKU: {{ $stock->product->sku }}</div>
                                @if ($stock->product->barcode)
                                    <div class="mt-0.5 text-xs text-slate-500">Barcode: <span class="font-mono text-slate-700">{{ $stock->product->barcode }}</span></div>
                                @endif
                            </div>

                            @if ($isLowStock)
                                <span class="shrink-0 rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-100">Low</span>
                            @else
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Safe</span>
                            @endif
                        </div>

                        <div class="mt-3 grid gap-2 text-sm sm:grid-cols-3">
                            <div class="rounded-2xl bg-slate-50 p-2">
                                <div class="text-xs text-slate-500">Warehouse</div>
                                <div class="mt-1 font-medium text-slate-900 text-sm">{{ $stock->warehouse->name }}</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-2">
                                <div class="text-xs text-slate-500">Category</div>
                                <div class="mt-1 font-medium text-slate-900 text-sm">{{ $stock->product->category?->name ?? '-' }}</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-2">
                                <div class="text-xs text-slate-500">Quantity</div>
                                <div class="mt-1 font-semibold text-slate-900 text-sm">
                                    {{ number_format($stock->quantity, 2, ',', '.') }}
                                    {{ $stock->product->unit?->abbreviation }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-end">
                            <button type="button"
                                    class="stock-detail-button inline-flex items-center justify-center gap-1 rounded-xl bg-sky-500 px-3 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600"
                                    data-stock-detail="{{ base64_encode(json_encode($stockDetail)) }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">
                        Belum ada data stok.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 sm:p-5">
                {{ $stocks->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lowStockCountOnPage = {{ $lowStockCountOnPage }};

            function showToast(options) {
                if (window.Toast) {
                    Toast.fire(options);
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: options.icon || 'info',
                        title: options.title || '',
                        timer: 2200,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                }
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            if (lowStockCountOnPage > 0) {
                showToast({
                    icon: 'warning',
                    title: `${lowStockCountOnPage} item low stock di halaman ini`
                });
            }

            document.querySelectorAll('.stock-detail-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const detail = JSON.parse(atob(button.dataset.stockDetail));
                    const statusClass = detail.status === 'Low Stock' ? 'text-red-700' : 'text-emerald-700';

                    const html = `
                        <div class="text-left text-sm">
                            <div class="rounded-2xl bg-sky-50 p-4 ring-1 ring-sky-100">
                                <div class="font-semibold text-slate-900">${escapeHtml(detail.product)}</div>
                                <div class="mt-1 text-xs text-slate-500">SKU: ${escapeHtml(detail.sku)}</div>
                                <div class="mt-1 text-xs text-slate-500">Barcode: <span class="font-mono">${escapeHtml(detail.barcode)}</span></div>
                            </div>

                            <div class="mt-4 grid gap-3 text-sm">
                                <div class="flex justify-between gap-3">
                                    <span class="text-slate-500">Warehouse</span>
                                    <span class="font-medium text-slate-900">${escapeHtml(detail.warehouse)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-slate-500">Category</span>
                                    <span class="font-medium text-slate-900">${escapeHtml(detail.category)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-slate-500">Quantity</span>
                                    <span class="font-medium text-slate-900">${escapeHtml(detail.quantity)} ${escapeHtml(detail.unit)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-slate-500">Alert Level</span>
                                    <span class="font-medium text-slate-900">${escapeHtml(detail.alert_level)} ${escapeHtml(detail.unit)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-slate-500">Status</span>
                                    <span class="font-semibold ${statusClass}">${escapeHtml(detail.status)}</span>
                                </div>
                            </div>
                        </div>
                    `;

                    if (window.Swal) {
                        Swal.fire({
                            title: 'Stock Detail',
                            html: html,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#0ea5e9',
                            width: 520
                        });
                        return;
                    }

                    alert(`${detail.product}\nStock: ${detail.quantity} ${detail.unit}\nStatus: ${detail.status}`);
                });
            });
        });
    </script>
</x-layouts.app>