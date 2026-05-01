<x-layouts.app :title="__('Profit Report')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profit Report</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Laporan profit kotor berdasarkan periode.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="button"
                        data-copy-report-summary
                        data-copy-title="Ringkasan profit disalin"
                        data-summary="Profit Report {{ $from->format('Y-m-d') }} - {{ $to->format('Y-m-d') }} | Gross Sales: Rp {{ number_format($grossSales, 0, ',', '.') }} | Refund: Rp {{ number_format($refundTotal, 0, ',', '.') }} | COGS: Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }} | Gross Profit: Rp {{ number_format($grossProfit, 0, ',', '.') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Copy Summary
                </button>

                <a href="{{ route('owner.reports.profit.export', request()->query()) }}"
                   data-export-confirm
                   data-export-title="Export profit report?"
                   data-export-text="Profit report sesuai filter tanggal saat ini akan didownload sebagai CSV."
                   class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                    Export CSV
                </a>

                <a href="{{ route('owner.dashboard') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('owner.reports.profit') }}" class="grid gap-3 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date"
                           name="from"
                           value="{{ $from->format('Y-m-d') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">To</label>
                    <input type="date"
                           name="to"
                           value="{{ $to->format('Y-m-d') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Filter
                    </button>

                    <a href="{{ route('owner.reports.profit') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Gross Sales</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    Rp {{ number_format($grossSales, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Refund</div>
                <div class="mt-2 text-xl font-bold text-red-700">
                    Rp {{ number_format($refundTotal, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">COGS</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Gross Profit</div>
                <div class="mt-2 text-xl font-bold text-green-700">
                    Rp {{ number_format($grossProfit, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="font-semibold text-gray-900">Top Profit Products</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty Sold</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Sales</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Profit</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($topProfitProducts as $product)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $product->product_name }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        SKU: {{ $product->sku }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    {{ number_format($product->total_quantity, 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right text-gray-600">
                                    Rp {{ number_format($product->total_cost, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-green-700">
                                    Rp {{ number_format($product->gross_profit, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada data profit pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-900">
                                Summary
                            </td>

                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                Rp {{ number_format($netSales, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right font-bold text-green-700">
                                Rp {{ number_format($grossProfit, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
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