<x-layouts.app :title="$refund->refund_number">
    <div class="p-6">
        <div class="mx-auto max-w-4xl space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Refund {{ $refund->refund_number }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Untuk invoice {{ $refund->sale->invoice_number }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <button type="button"
                            id="copyRefundNumberButton"
                            data-refund-number="{{ $refund->refund_number }}"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Copy Number
                    </button>

                    <a href="{{ route('cashier.sales.show', $refund->sale) }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back to Invoice
                    </a>

                    <a href="{{ route('cashier.sales.index') }}"
                       class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Sales History
                    </a>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Refund Date</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $refund->refunded_at?->format('d M Y H:i') }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Processed By</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $refund->user->name }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Method</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ strtoupper($refund->method) }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Total Refund</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        Rp {{ number_format($refund->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Refund Items</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Unit Price</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($refund->items as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">
                                            {{ $item->product_name }}
                                        </div>

                                        <div class="mt-1 text-xs text-gray-500">
                                            SKU: {{ $item->sku }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-600">
                                        {{ number_format($item->quantity, 2, ',', '.') }}
                                        {{ $item->unit_name }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-600">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900">
                                    Total
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    Rp {{ number_format($refund->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($refund->reason)
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h2 class="font-semibold text-gray-900">Reason</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ $refund->reason }}</p>
                </div>
            @endif
        </div>
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