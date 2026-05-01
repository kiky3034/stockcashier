<x-layouts.app :title="__('Stock Movements')">
    @php
        $currentMovements = $movements->getCollection();
        $movementTotal = method_exists($movements, 'total') ? $movements->total() : $movements->count();
        $stockInCount = $currentMovements->filter(fn ($movement) => $movement->quantity_change > 0)->count();
        $stockOutCount = $currentMovements->filter(fn ($movement) => $movement->quantity_change < 0)->count();
        $activeFilterCount = collect([$search, $warehouseId, $type])->filter(fn ($value) => filled($value))->count();
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Stock Movements"
            description="Pantau seluruh riwayat keluar-masuk stok per produk dan warehouse."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.stock-adjustments.create') }}">
                    + Stock Adjustment
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Total Movements"
                value="{{ number_format($movementTotal, 0, ',', '.') }}"
                description="Total riwayat movement sesuai filter"
                tone="sky"
            />

            <x-ui.stat-card
                label="Stock In"
                value="{{ number_format($stockInCount, 0, ',', '.') }}"
                description="Movement masuk pada halaman ini"
                tone="green"
            />

            <x-ui.stat-card
                label="Stock Out"
                value="{{ number_format($stockOutCount, 0, ',', '.') }}"
                description="Movement keluar pada halaman ini"
                tone="red"
            />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.stock-movements.index') }}" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
                    <div>
                        <label for="search" class="sr-only">Search</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari produk, SKU, atau barcode..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div>
                        <label for="warehouse_id" class="sr-only">Warehouse</label>
                        <select name="warehouse_id"
                                id="warehouse_id"
                                class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            <option value="">All Warehouses</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type" class="sr-only">Type</label>
                        <select name="type"
                                id="type"
                                class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            <option value="">All Types</option>
                            @foreach ($types as $movementType)
                                <option value="{{ $movementType }}" @selected($type === $movementType)>
                                    {{ str_replace('_', ' ', ucwords($movementType, '_')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="inline-flex flex-1 items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100 lg:flex-none">
                            Filter
                        </button>

                        <a href="{{ route('admin.stock-movements.index') }}"
                           class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100 lg:flex-none">
                            Reset
                        </a>
                    </div>
                </form>

                @if ($activeFilterCount > 0)
                    <div class="mt-3 rounded-2xl bg-sky-50 px-4 py-3 text-sm text-sky-700 ring-1 ring-sky-100">
                        {{ $activeFilterCount }} filter aktif. Gunakan tombol Reset untuk melihat semua movement.
                    </div>
                @endif
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Product</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Warehouse</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Type</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Before</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Change</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700">After</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($movements as $movement)
                            @php
                                $movementTypeLabel = str_replace('_', ' ', ucwords($movement->type, '_'));
                                $isIncrease = $movement->quantity_change > 0;
                                $movementDetail = [
                                    'date' => $movement->created_at->format('d M Y H:i'),
                                    'product' => $movement->product->name,
                                    'sku' => $movement->product->sku,
                                    'warehouse' => $movement->warehouse->name,
                                    'type' => $movementTypeLabel,
                                    'before' => number_format($movement->quantity_before, 2, ',', '.'),
                                    'change' => ($movement->quantity_change > 0 ? '+' : '') . number_format($movement->quantity_change, 2, ',', '.'),
                                    'after' => number_format($movement->quantity_after, 2, ',', '.'),
                                    'user' => $movement->user?->name ?? '-',
                                    'notes' => $movement->notes ?? '-',
                                ];
                            @endphp

                            <tr class="transition hover:bg-sky-50/40">
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">
                                    {{ $movement->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <div class="font-semibold text-slate-900">
                                        {{ $movement->product->name }}
                                    </div>
                                    <div class="mt-1 font-mono text-xs text-slate-500">
                                        SKU: {{ $movement->product->sku }}
                                    </div>

                                    @if ($movement->notes)
                                        <div class="mt-1 max-w-xs truncate text-xs text-slate-500">
                                            Note: {{ $movement->notes }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $movement->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full {{ $isIncrease ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-red-50 text-red-700 ring-red-100' }} px-2.5 py-1 text-xs font-semibold ring-1">
                                        {{ $movementTypeLabel }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right text-sm text-slate-600">
                                    {{ number_format($movement->quantity_before, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right text-sm">
                                    <span class="font-bold {{ $isIncrease ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $isIncrease ? '+' : '' }}{{ number_format($movement->quantity_change, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right text-sm font-semibold text-slate-900">
                                    {{ number_format($movement->quantity_after, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-right text-sm">
                                    <button type="button"
                                            title="Detail"
                                            aria-label="Detail stock movement"
                                            data-movement-detail="{{ base64_encode(json_encode($movementDetail, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-sm text-slate-500">
                                    Belum ada stock movement.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($movements as $movement)
                    @php
                        $movementTypeLabel = str_replace('_', ' ', ucwords($movement->type, '_'));
                        $isIncrease = $movement->quantity_change > 0;
                        $movementDetail = [
                            'date' => $movement->created_at->format('d M Y H:i'),
                            'product' => $movement->product->name,
                            'sku' => $movement->product->sku,
                            'warehouse' => $movement->warehouse->name,
                            'type' => $movementTypeLabel,
                            'before' => number_format($movement->quantity_before, 2, ',', '.'),
                            'change' => ($movement->quantity_change > 0 ? '+' : '') . number_format($movement->quantity_change, 2, ',', '.'),
                            'after' => number_format($movement->quantity_after, 2, ',', '.'),
                            'user' => $movement->user?->name ?? '-',
                            'notes' => $movement->notes ?? '-',
                        ];
                    @endphp

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900">{{ $movement->product->name }}</div>
                                <div class="mt-1 font-mono text-xs text-slate-500">SKU: {{ $movement->product->sku }}</div>
                            </div>

                            <button type="button"
                                    title="Detail"
                                    aria-label="Detail stock movement"
                                    data-movement-detail="{{ base64_encode(json_encode($movementDetail, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
                                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">{{ $movement->warehouse->name }}</span>
                            <span class="rounded-full {{ $isIncrease ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-red-50 text-red-700 ring-red-100' }} px-2.5 py-1 font-semibold ring-1">{{ $movementTypeLabel }}</span>
                            <span class="text-slate-500">{{ $movement->created_at->format('d M Y H:i') }}</span>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-3 rounded-2xl bg-slate-50 p-3 text-center text-xs">
                            <div>
                                <div class="text-slate-500">Before</div>
                                <div class="mt-1 font-semibold text-slate-900">{{ number_format($movement->quantity_before, 2, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Change</div>
                                <div class="mt-1 font-bold {{ $isIncrease ? 'text-emerald-700' : 'text-red-700' }}">
                                    {{ $isIncrease ? '+' : '' }}{{ number_format($movement->quantity_change, 2, ',', '.') }}
                                </div>
                            </div>
                            <div>
                                <div class="text-slate-500">After</div>
                                <div class="mt-1 font-semibold text-slate-900">{{ number_format($movement->quantity_after, 2, ',', '.') }}</div>
                            </div>
                        </div>

                        @if ($movement->notes)
                            <div class="mt-3 rounded-2xl bg-sky-50 p-3 text-xs text-sky-700 ring-1 ring-sky-100">
                                {{ $movement->notes }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada stock movement.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 sm:p-5">
                {{ $movements->links() }}
            </div>
        </x-ui.card>
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
                                    <div class="text-slate-500">Date</div><div class="font-semibold text-slate-900">${escapeHtml(detail.date)}</div>
                                    <div class="text-slate-500">Product</div><div class="font-semibold text-slate-900">${escapeHtml(detail.product)}</div>
                                    <div class="text-slate-500">SKU</div><div class="font-mono text-slate-900">${escapeHtml(detail.sku)}</div>
                                    <div class="text-slate-500">Warehouse</div><div class="font-semibold text-slate-900">${escapeHtml(detail.warehouse)}</div>
                                    <div class="text-slate-500">Type</div><div class="font-semibold text-slate-900">${escapeHtml(detail.type)}</div>
                                    <div class="text-slate-500">Before</div><div class="text-right font-semibold text-slate-900">${escapeHtml(detail.before)}</div>
                                    <div class="text-slate-500">Change</div><div class="text-right font-semibold text-slate-900">${escapeHtml(detail.change)}</div>
                                    <div class="text-slate-500">After</div><div class="text-right font-semibold text-slate-900">${escapeHtml(detail.after)}</div>
                                    <div class="text-slate-500">User</div><div class="font-semibold text-slate-900">${escapeHtml(detail.user)}</div>
                                </div>
                                <div class="mt-4 rounded-2xl bg-sky-50 p-3 ring-1 ring-sky-100">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-sky-700">Notes</div>
                                    <div class="mt-1 text-slate-700">${escapeHtml(detail.notes)}</div>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9',
                        width: 560
                    });
                });
            });
        });
    </script>
</x-layouts.app>
