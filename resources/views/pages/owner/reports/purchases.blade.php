<x-layouts.app :title="__('Purchase Report')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Purchase Report</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Laporan pembelian barang dari supplier.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="button"
                        data-copy-report-summary
                        data-copy-title="Ringkasan purchase disalin"
                        data-summary="Purchase Report {{ $from->format('Y-m-d') }} - {{ $to->format('Y-m-d') }} | Total Purchase: Rp {{ number_format($totalPurchase, 0, ',', '.') }} | Purchase Count: {{ number_format($purchaseCount, 0, ',', '.') }} | Total Items: {{ number_format($totalItems, 2, ',', '.') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Copy Summary
                </button>

                <a href="{{ route('owner.reports.purchases.export', request()->query()) }}"
                   data-export-confirm
                   data-export-title="Export purchase report?"
                   data-export-text="Purchase report sesuai filter saat ini akan didownload sebagai CSV."
                   class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Export CSV
                </a>

                <a href="{{ route('owner.dashboard') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Dashboard
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('owner.reports.purchases') }}" class="grid gap-3 md:grid-cols-6">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari purchase number..."
                       class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                <input type="date"
                       name="from"
                       value="{{ $from->format('Y-m-d') }}"
                       class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                <input type="date"
                       name="to"
                       value="{{ $to->format('Y-m-d') }}"
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

                    <a href="{{ route('owner.reports.purchases') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Purchase</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    Rp {{ number_format($totalPurchase, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Purchase Count</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    {{ number_format($purchaseCount, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Total Items</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    {{ number_format($totalItems, 2, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="font-semibold text-gray-900">Top Purchased Products</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Amount</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($topPurchasedProducts as $product)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $product->product_name }}</div>
                                    <div class="mt-1 text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    {{ number_format($product->total_quantity, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($product->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data produk pembelian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="font-semibold text-gray-900">Purchase List</h2>
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
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $purchase->purchase_number }}
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada purchase pada periode ini.
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
        const reportToast = window.Toast || {
            fire: function (options) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        ...options
                    });
                }
            }
        };

        document.querySelectorAll('[data-export-confirm]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                if (!window.Swal) {
                    window.location.href = link.href;
                    return;
                }

                Swal.fire({
                    title: link.dataset.exportTitle || 'Export CSV?',
                    text: link.dataset.exportText || 'Data sesuai filter saat ini akan didownload.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, export',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#111827',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        reportToast.fire({
                            icon: 'info',
                            title: 'Menyiapkan file CSV...'
                        });

                        window.location.href = link.href;
                    }
                });
            });
        });

        document.querySelectorAll('[data-copy-report-summary]').forEach(function (button) {
            button.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(button.dataset.summary || '');

                    reportToast.fire({
                        icon: 'success',
                        title: button.dataset.copyTitle || 'Ringkasan disalin'
                    });
                } catch (error) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyalin',
                            text: 'Clipboard tidak tersedia di browser ini.'
                        });
                    }
                }
            });
        });

        @if (request()->query())
            reportToast.fire({
                icon: 'info',
                title: 'Filter laporan diterapkan'
            });
        @endif
    });
</script>

</x-layouts.app>