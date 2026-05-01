<x-layouts.app :title="__('Profit Report')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Profit Report"
            description="Lihat gross profit berdasarkan penjualan, refund, dan cost of goods sold."
        >
            <x-slot:actions>
                <button type="button"
                        data-copy-report-summary
                        data-copy-title="Ringkasan profit disalin"
                        data-summary="Profit Report {{ $from->format('Y-m-d') }} - {{ $to->format('Y-m-d') }} | Gross Sales: Rp {{ number_format($grossSales, 0, ',', '.') }} | Refund: Rp {{ number_format($refundTotal, 0, ',', '.') }} | COGS: Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }} | Gross Profit: Rp {{ number_format($grossProfit, 0, ',', '.') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                    Copy Summary
                </button>

                <a href="{{ route('owner.reports.profit.export', request()->query()) }}"
                   data-export-confirm
                   data-export-title="Export profit report?"
                   data-export-text="Profit report sesuai filter tanggal saat ini akan didownload sebagai CSV."
                   class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">
                    Export CSV
                </a>

                <a href="{{ route('owner.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                    Dashboard
                </a>
            </x-slot:actions>
        </x-page-header>

        <x-ui.card>
            <form method="GET" action="{{ route('owner.reports.profit') }}" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">From</label>
                    <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">To</label>
                    <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>
                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" class="rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">Filter</button>
                    <a href="{{ route('owner.reports.profit') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-sky-50">Reset</a>
                </div>
            </form>
        </x-ui.card>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.stat-card label="Gross Sales" value="Rp {{ number_format($grossSales, 0, ',', '.') }}" tone="sky" />
            <x-ui.stat-card label="Refund" value="Rp {{ number_format($refundTotal, 0, ',', '.') }}" tone="red" />
            <x-ui.stat-card label="COGS" value="Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }}" tone="amber" />
            <x-ui.stat-card label="Gross Profit" value="Rp {{ number_format($grossProfit, 0, ',', '.') }}" tone="green" />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <h2 class="font-semibold text-slate-900">Top Profit Products</h2>
                <p class="mt-1 text-sm text-slate-500">Produk dengan kontribusi profit tertinggi pada periode ini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-sky-50/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Product</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Qty Sold</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Sales</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($topProfitProducts as $product)
                            <tr class="hover:bg-sky-50/40">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $product->product_name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600">{{ number_format($product->total_quantity, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-slate-600">Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-slate-600">Rp {{ number_format($product->total_cost, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700">Rp {{ number_format($product->gross_profit, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">Tidak ada data profit pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-sky-50/70">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right font-bold text-slate-900">Summary</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">Rp {{ number_format($netSales, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">Rp {{ number_format($costOfGoodsSold, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-ui.card>
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
                    confirmButtonColor: '#0ea5e9',
                    cancelButtonColor: '#64748b'
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
                const summary = button.dataset.summary || '';
                const title = button.dataset.copyTitle || 'Ringkasan disalin';

                try {
                    await navigator.clipboard.writeText(summary);

                    reportToast.fire({
                        icon: 'success',
                        title: title
                    });
                } catch (error) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyalin',
                            text: 'Browser tidak mengizinkan akses clipboard.',
                            confirmButtonColor: '#0ea5e9'
                        });
                    }
                }
            });
        });
    });
</script>

</x-layouts.app>
