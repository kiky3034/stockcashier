<x-layouts.app :title="__('Sales History')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Sales History"
            description="Riwayat transaksi penjualan, print receipt, dan cek detail invoice."
        >
            <x-slot:actions>
                {{-- Kosongkan actions karena tombol sudah dipindahkan --}}
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white p-4 sm:p-5">
                <form method="GET" action="{{ route('cashier.sales.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari invoice..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <x-ui.button-primary type="submit">
                            Search
                        </x-ui.button-primary>

                        @if ($search)
                            <x-ui.link-button href="{{ route('cashier.sales.index') }}" variant="secondary">
                                Reset
                            </x-ui.link-button>
                        @endif

                        {{-- Tombol New Sale di sini --}}
                        <a href="{{ route('cashier.pos.index') }}" 
                           class="inline-flex items-center justify-center gap-1 rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            New Sale
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Invoice</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Date</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Cashier</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Warehouse</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Total</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($sales as $sale)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-3 py-3">
                                    <div class="text-sm font-bold text-slate-900">{{ $sale->invoice_number }}</div>
                                </td>

                                <td class="px-3 py-3 text-xs text-slate-600">
                                    {{ $sale->sold_at?->format('d M Y H:i') }}
                                 </td>

                                <td class="px-3 py-3 text-xs text-slate-600">
                                    {{ $sale->cashier->name }}
                                 </td>

                                <td class="px-3 py-3 text-xs text-slate-600">
                                    {{ $sale->warehouse->name }}
                                 </td>

                                <td class="px-3 py-3 text-sm font-bold text-slate-900">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                 </td>

                                <td class="px-3 py-3">
                                    @if ($sale->status === 'completed')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Completed
                                        </span>
                                    @elseif ($sale->status === 'partially_refunded')
                                        <span class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-100">
                                            Partial Refund
                                        </span>
                                    @elseif ($sale->status === 'refunded')
                                        <span class="inline-flex rounded-full bg-sky-50 px-2 py-0.5 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                            Refunded
                                        </span>
                                    @elseif ($sale->status === 'voided')
                                        <span class="inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                            Voided
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-100">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    @endif
                                 </td>

                                <td class="px-3 py-3">
                                    <div class="flex gap-1">
                                        <button type="button"
                                                class="copy-invoice-button inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-invoice="{{ $sale->invoice_number }}"
                                                title="Copy invoice"
                                                aria-label="Copy invoice">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('cashier.sales.receipt', $sale) }}"
                                           target="_blank"
                                           class="print-receipt-link inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                           data-invoice="{{ $sale->invoice_number }}"
                                           title="Print receipt"
                                           aria-label="Print receipt">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M6 9V2h12v7" />
                                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                                <path d="M6 14h12v8H6z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('cashier.sales.show', $sale) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-sky-500 text-white shadow-sm transition hover:bg-sky-600"
                                           title="Detail"
                                           aria-label="Detail">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                    </div>
                                 </td>
                             </tr>
                        @empty
                             <tr>
                                <td colspan="7" class="px-3 py-10 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-500 ring-1 ring-sky-100">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 8v13H3V8" />
                                            <path d="M1 3h22v5H1z" />
                                            <path d="M10 12h4" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-3 font-bold text-slate-900">Belum ada transaksi</h3>
                                    <p class="mt-1 text-xs text-slate-500">Transaksi baru akan muncul di sini setelah kasir menyelesaikan penjualan.</p>
                                 </td>
                             </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 lg:hidden">
                @forelse ($sales as $sale)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate font-bold text-slate-900">{{ $sale->invoice_number }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $sale->sold_at?->format('d M Y H:i') }}</div>
                            </div>

                            @if ($sale->status === 'completed')
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Completed</span>
                            @elseif ($sale->status === 'partially_refunded')
                                <span class="shrink-0 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-100">Partial</span>
                            @elseif ($sale->status === 'refunded')
                                <span class="shrink-0 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">Refunded</span>
                            @elseif ($sale->status === 'voided')
                                <span class="shrink-0 rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-100">Voided</span>
                            @else
                                <span class="shrink-0 rounded-full bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-100">{{ ucfirst($sale->status) }}</span>
                            @endif
                        </div>

                        <div class="mt-3 grid gap-2 text-sm sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-2">
                                <div class="text-xs font-medium text-slate-500">Cashier</div>
                                <div class="mt-1 font-semibold text-slate-800">{{ $sale->cashier->name }}</div>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-2">
                                <div class="text-xs font-medium text-slate-500">Warehouse</div>
                                <div class="mt-1 font-semibold text-slate-800">{{ $sale->warehouse->name }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between rounded-2xl bg-sky-50 px-3 py-2 ring-1 ring-sky-100">
                            <span class="text-xs font-medium text-sky-700">Total</span>
                            <span class="text-base font-black text-sky-700">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-3 grid grid-cols-3 gap-2">
                            <button type="button"
                                    class="copy-invoice-button inline-flex items-center justify-center rounded-xl border border-slate-200 px-2 py-1.5 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                    data-invoice="{{ $sale->invoice_number }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" />
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                </svg>
                            </button>

                            <a href="{{ route('cashier.sales.receipt', $sale) }}"
                               target="_blank"
                               class="print-receipt-link inline-flex items-center justify-center rounded-xl border border-slate-200 px-2 py-1.5 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                               data-invoice="{{ $sale->invoice_number }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9V2h12v7" />
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                    <path d="M6 14h12v8H6z" />
                                </svg>
                            </a>

                            <a href="{{ route('cashier.sales.show', $sale) }}"
                               class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-2 py-1.5 text-white shadow-sm transition hover:bg-sky-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                        <h3 class="font-bold text-slate-900">Belum ada transaksi</h3>
                        <p class="mt-1 text-xs text-slate-500">Mulai transaksi baru dari halaman POS.</p>
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 bg-white p-4 sm:p-5">
                {{ $sales->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function notifyToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: title,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            }

            document.querySelectorAll('.copy-invoice-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const invoice = button.dataset.invoice || '';

                    if (!invoice) {
                        notifyToast('error', 'Invoice tidak ditemukan');
                        return;
                    }

                    navigator.clipboard.writeText(invoice).then(function () {
                        notifyToast('success', 'Invoice berhasil disalin');
                    }).catch(function () {
                        notifyToast('error', 'Gagal menyalin invoice');
                    });
                });
            });

            document.querySelectorAll('.print-receipt-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    notifyToast('info', 'Membuka receipt ' + (link.dataset.invoice || ''));
                });
            });

            @if ($search)
                notifyToast('info', 'Filter invoice aktif');
            @endif
        });
    </script>
</x-layouts.app>