<x-layouts.app :title="$refund->refund_number">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Refund {{ $refund->refund_number }}"
            description="Detail refund untuk invoice {{ $refund->sale->invoice_number }}."
        >
            <x-slot:actions>
                <button type="button"
                        id="copyRefundNumberButton"
                        data-refund-number="{{ $refund->refund_number }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                    </svg>
                    Copy Number
                </button>

                <x-ui.link-button href="{{ route('cashier.sales.show', $refund->sale) }}" variant="secondary">
                    Back to Invoice
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('cashier.sales.index') }}">
                    Sales History
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-3xl border border-sky-100 bg-gradient-to-r from-sky-500 via-sky-400 to-cyan-400 text-white shadow-sm">
            <div class="p-5 sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/90">
                            Refund Completed
                        </div>
                        <h2 class="mt-3 text-2xl font-bold tracking-tight">
                            Rp {{ number_format($refund->total_amount, 0, ',', '.') }}
                        </h2>
                        <p class="mt-1 text-sm text-sky-50">
                            {{ $refund->items->count() }} item dikembalikan · {{ $refund->refunded_at?->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white/15 px-4 py-3 ring-1 ring-white/20">
                        <div class="text-xs font-semibold uppercase tracking-wide text-sky-50">Invoice</div>
                        <div class="mt-1 font-bold text-white">{{ $refund->sale->invoice_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.stat-card
                label="Refund Date"
                value="{{ $refund->refunded_at?->format('d M Y H:i') }}"
                tone="sky"
            />

            <x-ui.stat-card
                label="Processed By"
                value="{{ $refund->user->name }}"
                tone="slate"
            />

            <x-ui.stat-card
                label="Method"
                value="{{ strtoupper($refund->method) }}"
                tone="amber"
            />

            <x-ui.stat-card
                label="Total Refund"
                value="Rp {{ number_format($refund->total_amount, 0, ',', '.') }}"
                tone="red"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Refund Items</h2>
                    <p class="mt-1 text-sm text-slate-500">Daftar produk yang dikembalikan pada refund ini.</p>
                </div>

                <div class="inline-flex w-fit items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                    {{ $refund->items->count() }} items
                </div>
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Product</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700">Qty</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700">Unit Price</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($refund->items as $item)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $item->product_name }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        SKU: <span class="font-mono">{{ $item->sku }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right text-slate-600">
                                    {{ number_format($item->quantity, 2, ',', '.') }}
                                    {{ $item->unit_name }}
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

                    <tfoot class="bg-sky-50">
                        <tr>
                            <td colspan="3" class="px-5 py-4 text-right font-bold text-slate-900">
                                Total Refund
                            </td>
                            <td class="px-5 py-4 text-right text-lg font-black text-sky-700">
                                Rp {{ number_format($refund->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="divide-y divide-slate-100 md:hidden">
                @foreach ($refund->items as $item)
                    <div class="p-4">
                        <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                        <div class="mt-1 text-xs text-slate-500">SKU: <span class="font-mono">{{ $item->sku }}</span></div>

                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-2xl bg-slate-50 p-3">
                                <div class="text-xs text-slate-500">Qty</div>
                                <div class="mt-1 font-semibold text-slate-900">
                                    {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit_name }}
                                </div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-3">
                                <div class="text-xs text-slate-500">Unit Price</div>
                                <div class="mt-1 font-semibold text-slate-900">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="col-span-2 rounded-2xl bg-sky-50 p-3 ring-1 ring-sky-100">
                                <div class="text-xs text-sky-700">Subtotal</div>
                                <div class="mt-1 text-lg font-bold text-sky-700">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bg-sky-50 p-4">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900">Total Refund</span>
                        <span class="text-lg font-black text-sky-700">
                            Rp {{ number_format($refund->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </x-ui.card>

        @if ($refund->reason)
            <x-ui.card>
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900">Reason</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $refund->reason }}</p>
                    </div>
                </div>
            </x-ui.card>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButton = document.getElementById('copyRefundNumberButton');

            function notifyToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon,
                        title,
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true
                    });
                }
            }

            if (copyButton) {
                copyButton.addEventListener('click', async function () {
                    const refundNumber = copyButton.dataset.refundNumber;

                    try {
                        await navigator.clipboard.writeText(refundNumber);
                        notifyToast('success', 'Refund number berhasil disalin.');
                    } catch (error) {
                        notifyToast('error', 'Gagal menyalin refund number.');
                    }
                });
            }
        });
    </script>
</x-layouts.app>
