<x-layouts.app :title="$sale->invoice_number">
    @php
        $statusClasses = [
            'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'partially_refunded' => 'bg-amber-50 text-amber-700 ring-amber-100',
            'refunded' => 'bg-orange-50 text-orange-700 ring-orange-100',
            'voided' => 'bg-red-50 text-red-700 ring-red-100',
        ];

        $statusClass = $statusClasses[$sale->status] ?? 'bg-slate-50 text-slate-700 ring-slate-100';
        $statusLabel = str_replace('_', ' ', ucfirst($sale->status));
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Invoice {{ $sale->invoice_number }}"
            description="Detail transaksi penjualan pada {{ $sale->sold_at?->format('d M Y H:i') }}."
        >
            <x-slot:actions>
                <button type="button"
                        id="copyInvoiceButton"
                        data-invoice="{{ $sale->invoice_number }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                    </svg>
                    Copy
                </button>

                <a href="{{ route('cashier.sales.receipt', $sale) }}"
                   target="_blank"
                   id="printReceiptLink"
                   data-invoice="{{ $sale->invoice_number }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9V2h12v7" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <path d="M6 14h12v8H6z" />
                    </svg>
                    Print
                </a>

                @if (in_array($sale->status, ['completed', 'partially_refunded']))
                    <a href="{{ route('cashier.sales.refunds.create', $sale) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700 shadow-sm transition hover:bg-amber-100 focus:outline-none focus:ring-4 focus:ring-amber-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7v6h6" />
                            <path d="M21 17a9 9 0 0 0-15-6.7L3 13" />
                        </svg>
                        Refund
                    </a>
                @endif

                <a href="{{ route('cashier.pos.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    New Sale
                </a>

                <a href="{{ route('cashier.sales.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    Back
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-3xl border border-sky-100 bg-gradient-to-r from-sky-500 via-cyan-500 to-blue-600 shadow-sm">
            <div class="p-5 text-white sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/90 ring-1 ring-white/20">
                            Sales Detail
                        </div>
                        <h2 class="mt-3 text-2xl font-black tracking-tight sm:text-3xl">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </h2>
                        <p class="mt-2 text-sm text-sky-50">
                            {{ $sale->items->count() }} item transaksi • Kasir {{ $sale->cashier?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="flex w-fit items-center rounded-full bg-white px-4 py-2 text-sm font-bold shadow-sm ring-1 ring-white/30 {{ $statusClass }}">
                        {{ $statusLabel }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Cashier"
                value="{{ $sale->cashier?->name ?? '-' }}"
                description="User yang membuat transaksi"
                tone="sky"
            />

            <x-ui.stat-card
                label="Warehouse"
                value="{{ $sale->warehouse?->name ?? '-' }}"
                description="Sumber stok transaksi"
                tone="slate"
            />

            <x-ui.stat-card
                label="Status"
                value="{{ $statusLabel }}"
                description="Status invoice saat ini"
                tone="{{ $sale->status === 'voided' ? 'red' : ($sale->status === 'completed' ? 'green' : 'amber') }}"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 p-4 sm:p-5">
                <div>
                    <h2 class="font-bold text-slate-900">Items</h2>
                    <p class="mt-1 text-sm text-slate-500">Daftar produk dalam invoice ini.</p>
                </div>
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Product</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600">Qty</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600">Price</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-600">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($sale->items as $item)
                            <tr class="hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">SKU: {{ $item->sku }}</div>
                                </td>

                                <td class="px-5 py-4 text-right text-slate-600">
                                    {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit_name }}

                                    @if ($item->refundItems->sum('quantity') > 0)
                                        <div class="mt-1 text-xs font-semibold text-red-600">
                                            Refunded: {{ number_format($item->refundItems->sum('quantity'), 2, ',', '.') }} {{ $item->unit_name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right text-slate-600">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-right font-bold text-slate-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 md:hidden">
                @foreach ($sale->items as $item)
                    <div class="p-4">
                        <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                        <div class="mt-1 text-xs text-slate-500">SKU: {{ $item->sku }}</div>

                        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <div class="text-xs text-slate-500">Qty</div>
                                <div class="font-semibold text-slate-800">
                                    {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit_name }}
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-slate-500">Subtotal</div>
                                <div class="font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        @if ($item->refundItems->sum('quantity') > 0)
                            <div class="mt-3 rounded-xl bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                Refunded: {{ number_format($item->refundItems->sum('quantity'), 2, ',', '.') }} {{ $item->unit_name }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-ui.card>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-ui.card>
                <h2 class="font-bold text-slate-900">Payment</h2>
                <p class="mt-1 text-sm text-slate-500">Metode pembayaran yang digunakan.</p>

                <div class="mt-5 space-y-3 text-sm">
                    @forelse ($sale->payments as $payment)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold text-slate-700">{{ strtoupper($payment->method) }}</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>

                            @if ($payment->reference_number)
                                <div class="mt-2 text-xs text-slate-500">Ref: {{ $payment->reference_number }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">
                            Tidak ada data pembayaran.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="font-bold text-slate-900">Summary</h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan nilai transaksi.</p>

                <div class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Tax</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-cyan-50 p-4 ring-1 ring-sky-100">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-bold text-sky-900">Total</span>
                            <span class="text-xl font-black text-sky-700">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Paid</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Change</span>
                        <span class="font-semibold text-emerald-700">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-ui.card>
        </div>

        @if ($sale->status === 'completed' && $sale->refunds->isEmpty())
            <div class="rounded-3xl border border-red-200 bg-red-50 p-5 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-bold text-red-900">Void Transaction</h2>
                        <p class="mt-1 text-sm text-red-700">
                            Void akan membatalkan transaksi ini dan mengembalikan stok produk.
                        </p>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('cashier.sales.void', $sale) }}"
                      class="mt-4 space-y-3"
                      data-confirm-submit
                      data-confirm-title="Void transaksi?"
                      data-confirm-text="Transaksi {{ $sale->invoice_number }} akan dibatalkan dan stok produk akan dikembalikan."
                      data-confirm-button="Ya, void transaksi"
                      data-confirm-icon="warning">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="reason" class="block text-sm font-semibold text-red-900">Reason</label>

                        <textarea id="reason"
                                  name="reason"
                                  rows="3"
                                  placeholder="Contoh: Salah input barang / customer batal"
                                  class="mt-2 w-full rounded-2xl border-red-300 text-sm focus:border-red-700 focus:ring-red-700">{{ old('reason') }}</textarea>

                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100">
                        Void Transaction
                    </button>
                </form>
            </div>
        @endif

        @if ($sale->refunds->isNotEmpty())
            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-100 p-4 sm:p-5">
                    <h2 class="font-bold text-slate-900">Refund History</h2>
                    <p class="mt-1 text-sm text-slate-500">Riwayat refund untuk invoice ini.</p>
                </div>

                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Refund Number</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Date</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">User</th>
                                <th class="px-5 py-3 text-left font-semibold text-slate-600">Method</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Amount</th>
                                <th class="px-5 py-3 text-right font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($sale->refunds as $refund)
                                <tr class="hover:bg-sky-50/40">
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $refund->refund_number }}</td>
                                    <td class="px-5 py-4 text-slate-600">{{ $refund->refunded_at?->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-4 text-slate-600">{{ $refund->user?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-slate-600">{{ strtoupper($refund->method) }}</td>
                                    <td class="px-5 py-4 text-right font-bold text-slate-900">Rp {{ number_format($refund->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('cashier.refunds.show', $refund) }}"
                                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divide-y divide-slate-100 md:hidden">
                    @foreach ($sale->refunds as $refund)
                        <div class="p-4">
                            <div class="font-semibold text-slate-900">{{ $refund->refund_number }}</div>
                            <div class="mt-1 text-xs text-slate-500">{{ $refund->refunded_at?->format('d M Y H:i') }} • {{ strtoupper($refund->method) }}</div>
                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="text-sm font-bold text-slate-900">Rp {{ number_format($refund->total_amount, 0, ',', '.') }}</div>
                                <a href="{{ route('cashier.refunds.show', $refund) }}"
                                   class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        @if ($sale->notes)
            <x-ui.card>
                <h2 class="font-bold text-slate-900">Notes</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $sale->notes }}</p>
            </x-ui.card>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function notifyToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            const copyInvoiceButton = document.getElementById('copyInvoiceButton');

            if (copyInvoiceButton) {
                copyInvoiceButton.addEventListener('click', function () {
                    const invoice = copyInvoiceButton.dataset.invoice || '';

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
            }

            const printReceiptLink = document.getElementById('printReceiptLink');

            if (printReceiptLink) {
                printReceiptLink.addEventListener('click', function () {
                    notifyToast('info', 'Membuka receipt untuk invoice ' + (printReceiptLink.dataset.invoice || ''));
                });
            }
        });
    </script>
</x-layouts.app>
