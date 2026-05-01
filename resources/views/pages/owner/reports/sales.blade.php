<x-layouts.app :title="__('Sales Report')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Sales Report"
            description="Pantau penjualan, refund, dan transaksi berdasarkan periode tanggal."
        >
            <x-slot:actions>
                <button type="button"
                        data-copy-report-summary
                        data-copy-title="Ringkasan sales disalin"
                        data-summary="Sales Report {{ $from->format('Y-m-d') }} - {{ $to->format('Y-m-d') }} | Gross Sales: Rp {{ number_format($grossSales, 0, ',', '.') }} | Refund: Rp {{ number_format($refundTotal, 0, ',', '.') }} | Net Sales: Rp {{ number_format($netSales, 0, ',', '.') }} | Transactions: {{ number_format($transactionCount, 0, ',', '.') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                    Copy Summary
                </button>

                <a href="{{ route('owner.reports.sales.export', request()->query()) }}"
                   data-export-confirm
                   data-export-title="Export sales report?"
                   data-export-text="Sales report sesuai filter tanggal saat ini akan didownload sebagai CSV."
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
            <form method="GET" action="{{ route('owner.reports.sales') }}" class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">From</label>
                    <input type="date"
                           name="from"
                           value="{{ $from->format('Y-m-d') }}"
                           class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">To</label>
                    <input type="date"
                           name="to"
                           value="{{ $to->format('Y-m-d') }}"
                           class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">
                        Filter
                    </button>

                    <a href="{{ route('owner.reports.sales') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-sky-50">
                        Reset
                    </a>
                </div>
            </form>
        </x-ui.card>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.stat-card label="Gross Sales" value="Rp {{ number_format($grossSales, 0, ',', '.') }}" tone="sky" />
            <x-ui.stat-card label="Refund" value="Rp {{ number_format($refundTotal, 0, ',', '.') }}" tone="red" />
            <x-ui.stat-card label="Net Sales" value="Rp {{ number_format($netSales, 0, ',', '.') }}" tone="green" />
            <x-ui.stat-card label="Transactions" value="{{ number_format($transactionCount, 0, ',', '.') }}" tone="slate" />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <h2 class="font-semibold text-slate-900">Sales List</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar transaksi pada periode yang dipilih.</p>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-sky-50/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Invoice</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Cashier</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-sky-50/40">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $sale->invoice_number }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $sale->sold_at?->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $sale->cashier->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $sale->warehouse->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                        {{ str_replace('_', ' ', ucfirst($sale->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-500">Tidak ada penjualan pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($sales as $sale)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $sale->invoice_number }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $sale->sold_at?->format('d M Y H:i') }}</div>
                            </div>
                            <div class="text-right font-bold text-slate-900">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="mt-3 grid gap-2 text-sm text-slate-600">
                            <div>Cashier: {{ $sale->cashier->name }}</div>
                            <div>Warehouse: {{ $sale->warehouse->name }}</div>
                            <div>Status: {{ str_replace('_', ' ', ucfirst($sale->status)) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">Tidak ada penjualan pada periode ini.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $sales->links() }}
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
