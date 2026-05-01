<x-layouts.app :title="__('Stocks')">
    @php
        $lowStockCountOnPage = $stocks->getCollection()
            ->filter(fn ($stock) => (float) $stock->quantity <= (float) $stock->product->stock_alert_level)
            ->count();
    @endphp

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
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
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

                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $stock->product->name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        SKU: {{ $stock->product->sku }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Barcode:
                                        <span class="font-mono">{{ $stock->product->barcode ?: '-' }}</span>
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
                                    @if ($isLowStock)
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            Safe
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <button type="button"
                                            class="stock-detail-button rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                            data-stock-detail="{{ base64_encode(json_encode($stockDetail)) }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
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
                        icon: options.icon || 'info',
                        title: options.title || '',
                        timer: 2200,
                        showConfirmButton: false
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
                    const statusClass = detail.status === 'Low Stock' ? 'text-red-700' : 'text-green-700';

                    const html = `
                        <div class="text-left text-sm">
                            <div class="rounded-lg bg-gray-50 p-4">
                                <div class="font-semibold text-gray-900">${escapeHtml(detail.product)}</div>
                                <div class="mt-1 text-xs text-gray-500">SKU: ${escapeHtml(detail.sku)}</div>
                                <div class="mt-1 text-xs text-gray-500">Barcode: <span class="font-mono">${escapeHtml(detail.barcode)}</span></div>
                            </div>

                            <div class="mt-4 grid gap-3 text-sm">
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500">Warehouse</span>
                                    <span class="font-medium text-gray-900">${escapeHtml(detail.warehouse)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500">Category</span>
                                    <span class="font-medium text-gray-900">${escapeHtml(detail.category)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500">Quantity</span>
                                    <span class="font-medium text-gray-900">${escapeHtml(detail.quantity)} ${escapeHtml(detail.unit)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500">Alert Level</span>
                                    <span class="font-medium text-gray-900">${escapeHtml(detail.alert_level)} ${escapeHtml(detail.unit)}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500">Status</span>
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
                            confirmButtonColor: '#111827',
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
