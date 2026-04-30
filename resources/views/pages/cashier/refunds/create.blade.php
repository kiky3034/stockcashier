<x-layouts.app :title="__('Create Refund')">
    <div class="p-6">
        <div class="mx-auto max-w-4xl space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Refund {{ $sale->invoice_number }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Pilih item dan quantity yang ingin direfund.
                    </p>
                </div>

                <a href="{{ route('cashier.sales.show', $sale) }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('cashier.sales.refunds.store', $sale) }}" class="space-y-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="text-sm text-gray-500">Invoice</div>
                        <div class="mt-1 font-semibold text-gray-900">{{ $sale->invoice_number }}</div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="text-sm text-gray-500">Cashier</div>
                        <div class="mt-1 font-semibold text-gray-900">{{ $sale->cashier->name }}</div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="text-sm text-gray-500">Warehouse</div>
                        <div class="mt-1 font-semibold text-gray-900">{{ $sale->warehouse->name }}</div>
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
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Sold Qty</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Refunded</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Available</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Refund Qty</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Unit Price</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($sale->items as $index => $item)
                                    @php
                                        $refundedQty = (float) $item->refundItems->sum('quantity');
                                        $availableQty = max((float) $item->quantity - $refundedQty, 0);
                                    @endphp

                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ $item->product_name }}
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                SKU: {{ $item->sku }}
                                            </div>

                                            <input type="hidden"
                                                   name="items[{{ $index }}][sale_item_id]"
                                                   value="{{ $item->id }}">
                                        </td>

                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($item->quantity, 2, ',', '.') }}
                                            {{ $item->unit_name }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-gray-600">
                                            {{ number_format($refundedQty, 2, ',', '.') }}
                                            {{ $item->unit_name }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-gray-900">
                                            {{ number_format($availableQty, 2, ',', '.') }}
                                            {{ $item->unit_name }}
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <input type="number"
                                                   name="items[{{ $index }}][quantity]"
                                                   value="{{ old('items.' . $index . '.quantity', 0) }}"
                                                   min="0"
                                                   max="{{ $availableQty }}"
                                                   step="0.01"
                                                   data-price="{{ $item->unit_price }}"
                                                   data-max="{{ $availableQty }}"
                                                   class="refund-qty w-28 rounded-lg border-gray-300 text-right text-sm focus:border-gray-900 focus:ring-gray-900"
                                                   @disabled($availableQty <= 0)>
                                        </td>

                                        <td class="px-4 py-3 text-right text-gray-600">
                                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm space-y-4">
                        <h2 class="font-semibold text-gray-900">Refund Info</h2>

                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700">
                                Refund Method
                            </label>
                            <select id="method"
                                    name="method"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                                <option value="card">Card</option>
                            </select>

                            @error('method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">
                                Reason
                            </label>
                            <textarea id="reason"
                                      name="reason"
                                      rows="4"
                                      placeholder="Contoh: Barang rusak / customer salah beli"
                                      class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('reason') }}</textarea>

                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h2 class="font-semibold text-gray-900">Refund Summary</h2>

                        <div class="mt-4 flex justify-between text-base">
                            <span class="text-gray-600">Estimated Refund</span>
                            <span id="refundTotalText" class="font-bold text-gray-900">Rp 0</span>
                        </div>

                        <p class="mt-3 text-sm text-gray-500">
                            Nilai refund dihitung dari quantity refund x harga jual item.
                        </p>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('cashier.sales.show', $sale) }}"
                               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                                    onclick="return confirm('Yakin ingin menyimpan refund ini? Stok akan dikembalikan.')">
                                Save Refund
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const qtyInputs = document.querySelectorAll('.refund-qty');
        const refundTotalText = document.getElementById('refundTotalText');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        }

        function calculateRefundTotal() {
            let total = 0;

            qtyInputs.forEach(input => {
                const quantity = Number(input.value || 0);
                const price = Number(input.dataset.price || 0);
                const max = Number(input.dataset.max || 0);

                if (quantity > max) {
                    input.value = max;
                }

                total += Number(input.value || 0) * price;
            });

            refundTotalText.textContent = formatRupiah(total);
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateRefundTotal);
        });

        calculateRefundTotal();
    </script>
</x-layouts.app>