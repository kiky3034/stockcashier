<x-layouts.app :title="__('Stock Movements')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Stock Movements</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Riwayat semua perubahan stok.
                </p>
            </div>

            <a href="{{ route('admin.stock-adjustments.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Stock Adjustment
            </a>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.stock-movements.index') }}" class="grid gap-3 md:grid-cols-4">
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

                    <select name="type"
                            class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        <option value="">All Types</option>
                        @foreach ($types as $movementType)
                            <option value="{{ $movementType }}" @selected($type === $movementType)>
                                {{ str_replace('_', ' ', ucwords($movementType, '_')) }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Filter
                        </button>

                        <a href="{{ route('admin.stock-movements.index') }}"
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Type</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Before</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Change</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">After</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($movements as $movement)
                            <tr>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $movement->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $movement->product->name }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        SKU: {{ $movement->product->sku }}
                                    </div>

                                    @if ($movement->notes)
                                        <div class="mt-1 text-xs text-gray-500">
                                            Note: {{ $movement->notes }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $movement->warehouse->name }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                        {{ str_replace('_', ' ', ucwords($movement->type, '_')) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    {{ number_format($movement->quantity_before, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    @if ($movement->quantity_change > 0)
                                        <span class="font-semibold text-green-700">
                                            +{{ number_format($movement->quantity_change, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="font-semibold text-red-700">
                                            {{ number_format($movement->quantity_change, 2, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right text-gray-900">
                                    {{ number_format($movement->quantity_after, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                                @php
                                    $movementDetail = [
                                        'date' => $movement->created_at->format('d M Y H:i'),
                                        'product' => $movement->product->name,
                                        'sku' => $movement->product->sku,
                                        'warehouse' => $movement->warehouse->name,
                                        'type' => str_replace('_', ' ', ucwords($movement->type, '_')),
                                        'before' => number_format($movement->quantity_before, 2, ',', '.'),
                                        'change' => ($movement->quantity_change > 0 ? '+' : '') . number_format($movement->quantity_change, 2, ',', '.'),
                                        'after' => number_format($movement->quantity_after, 2, ',', '.'),
                                        'user' => $movement->user?->name ?? '-',
                                        'notes' => $movement->notes ?? '-',
                                    ];
                                @endphp

                                <td class="px-4 py-3 text-right">
                                    <button type="button"
                                            data-movement-detail="{{ base64_encode(json_encode($movementDetail, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada stock movement.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            document.querySelectorAll('[data-movement-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const detail = JSON.parse(atob(button.dataset.movementDetail));

                    if (!window.Swal) {
                        alert(`${detail.product} - ${detail.type}`);
                        return;
                    }

                    Swal.fire({
                        title: 'Stock Movement Detail',
                        html: `
                            <div class="text-left text-sm">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="text-gray-500">Date</div><div class="font-semibold text-gray-900">${escapeHtml(detail.date)}</div>
                                    <div class="text-gray-500">Product</div><div class="font-semibold text-gray-900">${escapeHtml(detail.product)}</div>
                                    <div class="text-gray-500">SKU</div><div class="font-mono text-gray-900">${escapeHtml(detail.sku)}</div>
                                    <div class="text-gray-500">Warehouse</div><div class="font-semibold text-gray-900">${escapeHtml(detail.warehouse)}</div>
                                    <div class="text-gray-500">Type</div><div class="font-semibold text-gray-900">${escapeHtml(detail.type)}</div>
                                    <div class="text-gray-500">Before</div><div class="text-right font-semibold text-gray-900">${escapeHtml(detail.before)}</div>
                                    <div class="text-gray-500">Change</div><div class="text-right font-semibold text-gray-900">${escapeHtml(detail.change)}</div>
                                    <div class="text-gray-500">After</div><div class="text-right font-semibold text-gray-900">${escapeHtml(detail.after)}</div>
                                    <div class="text-gray-500">User</div><div class="font-semibold text-gray-900">${escapeHtml(detail.user)}</div>
                                </div>
                                <div class="mt-4 rounded-lg bg-gray-50 p-3">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Notes</div>
                                    <div class="mt-1 text-gray-700">${escapeHtml(detail.notes)}</div>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#111827',
                        width: 560
                    });
                });
            });
        });
    </script></x-layouts.app>