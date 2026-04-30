<x-layouts.app :title="$purchase->purchase_number">
    <div class="p-6">
        <div class="mx-auto max-w-5xl space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Purchase {{ $purchase->purchase_number }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $purchase->purchased_at?->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.purchases.create') }}"
                       class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        New Purchase
                    </a>

                    <a href="{{ route('admin.purchases.index') }}"
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

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Supplier</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $purchase->supplier->name }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Warehouse</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $purchase->warehouse->name }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Created By</div>
                    <div class="mt-1 font-semibold text-gray-900">{{ $purchase->user->name }}</div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="mt-1 font-semibold text-green-700">{{ ucfirst($purchase->status) }}</div>
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
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Unit Cost</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($purchase->items as $item)
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
                                        Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-gray-600">Subtotal</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($purchase->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-gray-600">Discount</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($purchase->discount_amount, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-gray-600">Tax</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($purchase->tax_amount, 0, ',', '.') }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if ($purchase->notes)
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <h2 class="font-semibold text-gray-900">Notes</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ $purchase->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>