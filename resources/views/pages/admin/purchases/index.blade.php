<x-layouts.app :title="__('Purchases')">
    @php
        $pageTotalAmount = $purchases->sum('total_amount');
        $pagePurchaseCount = $purchases->count();
        $pageSupplierCount = $purchases->pluck('supplier_id')->filter()->unique()->count();
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Purchases"
            description="Riwayat pembelian barang dari supplier dan stok masuk ke warehouse."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.purchases.create') }}">
                    <span class="mr-2">+</span>
                    New Purchase
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Purchases on Page"
                value="{{ number_format($pagePurchaseCount, 0, ',', '.') }}"
                description="Jumlah purchase pada halaman ini"
                tone="sky"
            />

            <x-ui.stat-card
                label="Total Amount"
                value="Rp {{ number_format($pageTotalAmount, 0, ',', '.') }}"
                description="Total purchase pada halaman ini"
                tone="green"
            />

            <x-ui.stat-card
                label="Suppliers"
                value="{{ number_format($pageSupplierCount, 0, ',', '.') }}"
                description="Supplier unik pada halaman ini"
                tone="slate"
            />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.purchases.index') }}" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px_auto]">
                    <div>
                        <label for="search" class="sr-only">Search</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nomor purchase..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                    </div>

                    <select name="supplier_id"
                            class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected($supplierId == $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="warehouse_id"
                            class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Warehouses</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="inline-flex flex-1 items-center justify-center rounded-2xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100 lg:flex-none">
                            Filter
                        </button>

                        <a href="{{ route('admin.purchases.index') }}"
                           class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 lg:flex-none">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Purchase Number</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Supplier</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">User</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Total</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($purchases as $purchase)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $purchase->purchase_number }}</div>
                                    <div class="mt-1 text-xs text-slate-500">ID: {{ $purchase->id }}</div>
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ $purchase->purchased_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ $purchase->supplier->name }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ $purchase->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-slate-600">
                                    {{ $purchase->user->name }}
                                </td>

                                <td class="px-4 py-3 text-right font-bold text-slate-900">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                data-copy-text="{{ $purchase->purchase_number }}"
                                                title="Copy number"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.purchases.show', $purchase) }}"
                                           title="Detail"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-500 text-white shadow-sm transition hover:bg-sky-600">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                    Belum ada purchase.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($purchases as $purchase)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $purchase->purchase_number }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $purchase->purchased_at?->format('d M Y H:i') }}</div>
                            </div>

                            <div class="text-right font-bold text-slate-900">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mt-3 grid gap-2 text-sm text-slate-600">
                            <div>Supplier: <span class="font-medium text-slate-900">{{ $purchase->supplier->name }}</span></div>
                            <div>Warehouse: <span class="font-medium text-slate-900">{{ $purchase->warehouse->name }}</span></div>
                            <div>User: <span class="font-medium text-slate-900">{{ $purchase->user->name }}</span></div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="button"
                                    data-copy-text="{{ $purchase->purchase_number }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                                Copy
                            </button>
                            <a href="{{ route('admin.purchases.show', $purchase) }}"
                               class="inline-flex flex-1 items-center justify-center rounded-2xl bg-sky-500 px-4 py-2 text-sm font-semibold text-white">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">Belum ada purchase.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $purchases->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon: icon, title: title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon: icon, title: title, timer: 1800, showConfirmButton: false });
                }
            }

            document.querySelectorAll('[data-copy-text]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const text = button.dataset.copyText || '';

                    try {
                        await navigator.clipboard.writeText(text);
                        showToast('success', 'Purchase number disalin.');
                    } catch (error) {
                        showToast('error', 'Gagal menyalin purchase number.');
                    }
                });
            });
        });
    </script>
</x-layouts.app>
