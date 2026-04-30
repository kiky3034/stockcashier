<x-layouts.app :title="$sale->invoice_number">
    <div class="p-6">
        <div class="mx-auto max-w-4xl space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Invoice {{ $sale->invoice_number }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $sale->sold_at?->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('cashier.pos.index') }}"
                       class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        New Sale
                    </a>

                    <a href="{{ route('cashier.sales.index') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Cashier</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $sale->cashier->name }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Warehouse</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $sale->warehouse->name }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ ucfirst($sale->status) }}</div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900">Items</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Qty</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Price</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($sale->items as $item)
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
                    </table>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h2 class="font-semibold text-gray-900">Payment</h2>

                    <div class="mt-4 space-y-2 text-sm">
                        @foreach ($sale->payments as $payment)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ strtoupper($payment->method) }}</span>
                                <span class="font-semibold text-gray-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ($payment->reference_number)
                                <div class="text-xs text-gray-500">
                                    Ref: {{ $payment->reference_number }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h2 class="font-semibold text-gray-900">Summary</h2>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($sale->subtotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between border-t border-gray-200 pt-3 text-base">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="font-bold text-gray-900">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Change</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($sale->notes)
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h2 class="font-semibold text-gray-900">Notes</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ $sale->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>