<x-layouts.app :title="__('Warehouses')">
    @php
        $warehouseItems = method_exists($warehouses, 'getCollection') ? $warehouses->getCollection() : collect($warehouses);
        $totalWarehouses = method_exists($warehouses, 'total') ? $warehouses->total() : $warehouseItems->count();
        $activeWarehouses = $warehouseItems->where('is_active', true)->count();
        $defaultWarehouse = $warehouseItems->firstWhere('is_default', true);
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Warehouses"
            description="Kelola lokasi penyimpanan stok dan gudang default StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.warehouses.create') }}">
                    + Add Warehouse
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Total Warehouses"
                value="{{ number_format($totalWarehouses, 0, ',', '.') }}"
                description="Semua lokasi penyimpanan"
                tone="sky"
            />

            <x-ui.stat-card
                label="Active on Page"
                value="{{ number_format($activeWarehouses, 0, ',', '.') }}"
                description="Warehouse aktif di halaman ini"
                tone="green"
            />

            <x-ui.stat-card
                label="Default Warehouse"
                value="{{ $defaultWarehouse?->code ?? '-' }}"
                description="{{ $defaultWarehouse?->name ?? 'Belum ada default di halaman ini' }}"
                tone="amber"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.warehouses.index') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto_auto]">
                    <div>
                        <label for="search" class="sr-only">Search</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama, kode, atau alamat warehouse..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.warehouses.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Warehouse</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Code</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Default</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-3 text-right text-sm font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($warehouses as $warehouse)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $warehouse->name }}</div>

                                    @if ($warehouse->address)
                                        <div class="mt-1 max-w-xl text-xs leading-5 text-slate-500">
                                            {{ $warehouse->address }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-xl bg-slate-50 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                            {{ $warehouse->code }}
                                        </span>

                                        <button type="button"
                                                title="Copy code"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-copy-warehouse-code="{{ $warehouse->code }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($warehouse->is_default)
                                        <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                            Default
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if ($warehouse->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                title="Detail"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-warehouse-detail
                                                data-name="{{ $warehouse->name }}"
                                                data-code="{{ $warehouse->code }}"
                                                data-address="{{ $warehouse->address ?: '-' }}"
                                                data-default="{{ $warehouse->is_default ? 'Yes' : 'No' }}"
                                                data-status="{{ $warehouse->is_active ? 'Active' : 'Inactive' }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                                           title="Edit"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.warehouses.destroy', $warehouse) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus warehouse?"
                                              data-confirm-text="Warehouse {{ $warehouse->name }} akan dihapus jika belum digunakan transaksi/stok."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Delete"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">
                                    Belum ada warehouse.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($warehouses as $warehouse)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $warehouse->name }}</div>
                                <div class="mt-1 font-mono text-xs text-slate-500">{{ $warehouse->code }}</div>
                            </div>

                            <div class="flex gap-2">
                                @if ($warehouse->is_default)
                                    <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">Default</span>
                                @endif

                                @if ($warehouse->is_active)
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Active</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">Inactive</span>
                                @endif
                            </div>
                        </div>

                        @if ($warehouse->address)
                            <p class="mt-3 text-sm leading-6 text-slate-500">{{ $warehouse->address }}</p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"
                                    data-copy-warehouse-code="{{ $warehouse->code }}">
                                Copy Code
                            </button>

                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"
                                    data-warehouse-detail
                                    data-name="{{ $warehouse->name }}"
                                    data-code="{{ $warehouse->code }}"
                                    data-address="{{ $warehouse->address ?: '-' }}"
                                    data-default="{{ $warehouse->is_default ? 'Yes' : 'No' }}"
                                    data-status="{{ $warehouse->is_active ? 'Active' : 'Inactive' }}">
                                Detail
                            </button>

                            <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.warehouses.destroy', $warehouse) }}"
                                  data-confirm-submit
                                  data-confirm-title="Hapus warehouse?"
                                  data-confirm-text="Warehouse {{ $warehouse->name }} akan dihapus jika belum digunakan transaksi/stok."
                                  data-confirm-button="Ya, hapus"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">Belum ada warehouse.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $warehouses->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function fireToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
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

            document.querySelectorAll('[data-copy-warehouse-code]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const code = button.dataset.copyWarehouseCode;

                    try {
                        await navigator.clipboard.writeText(code);
                        fireToast('success', 'Kode warehouse disalin.');
                    } catch (error) {
                        fireToast('error', 'Gagal menyalin kode warehouse.');
                    }
                });
            });

            document.querySelectorAll('[data-warehouse-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const html = `
                        <div class="text-left text-sm">
                            <div class="grid gap-3">
                                <div><strong>Name:</strong><br>${escapeHtml(button.dataset.name)}</div>
                                <div><strong>Code:</strong><br><code>${escapeHtml(button.dataset.code)}</code></div>
                                <div><strong>Address:</strong><br>${escapeHtml(button.dataset.address)}</div>
                                <div><strong>Default:</strong><br>${escapeHtml(button.dataset.default)}</div>
                                <div><strong>Status:</strong><br>${escapeHtml(button.dataset.status)}</div>
                            </div>
                        </div>
                    `;

                    if (window.Swal) {
                        Swal.fire({
                            title: 'Warehouse Detail',
                            html: html,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#0ea5e9',
                            width: 560,
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>
