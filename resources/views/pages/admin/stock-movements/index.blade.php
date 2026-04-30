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

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
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
</x-layouts.app>