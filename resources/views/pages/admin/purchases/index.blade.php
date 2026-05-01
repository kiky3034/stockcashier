<x-layouts.app :title="__('Purchases')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Purchases</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Riwayat pembelian barang dari supplier.
                </p>
            </div>

            <a href="{{ route('admin.purchases.create') }}"
               class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + New Purchase
            </a>
        </div>


        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.purchases.index') }}" class="grid gap-3 md:grid-cols-4">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nomor purchase..."
                           class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <select name="supplier_id"
                            class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected($supplierId == $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>

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

                        <a href="{{ route('admin.purchases.index') }}"
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Purchase Number</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Supplier</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Total</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">{{ $purchase->purchase_number }}</span>
                                        <button type="button"
                                                class="text-xs font-semibold text-gray-500 hover:text-gray-900"
                                                data-copy-text="{{ $purchase->purchase_number }}">
                                            Copy
                                        </button>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $purchase->purchased_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $purchase->supplier->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $purchase->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $purchase->user->name }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.purchases.show', $purchase) }}"
                                       class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada purchase.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon: icon, title: title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        timer: 1800,
                        showConfirmButton: false
                    });
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