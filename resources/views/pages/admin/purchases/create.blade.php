<x-layouts.app :title="__('Create Purchase')">
    <div class="p-6">
        <div class="mx-auto max-w-6xl space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">New Purchase</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Input barang masuk dari supplier.
                    </p>
                </div>

                <a href="{{ route('admin.purchases.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.purchases.store') }}" class="space-y-6">
                @csrf

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">
                                Supplier
                            </label>

                            <select id="supplier_id"
                                    name="supplier_id"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">- Select Supplier -</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('supplier_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="warehouse_id" class="block text-sm font-medium text-gray-700">
                                Warehouse Destination
                            </label>

                            <select id="warehouse_id"
                                    name="warehouse_id"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">- Select Warehouse -</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                        {{ $warehouse->name }} — {{ $warehouse->code }}
                                    </option>
                                @endforeach
                            </select>

                            @error('warehouse_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 p-4">
                        <h2 class="font-semibold text-gray-900">Purchase Items</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Isi quantity lebih dari 0 untuk produk yang dibeli.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Current Cost</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Quantity</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-700">Unit Cost</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">
                                                {{ $product->name }}
                                            </div>

                                            <div class="mt-1 text-xs text-gray-500">
                                                SKU: {{ $product->sku }}
                                                @if ($product->unit)
                                                    · Unit: {{ $product->unit->abbreviation }}
                                                @endif
                                            </div>

                                            <input type="hidden"
                                                   name="items[{{ $index }}][product_id]"
                                                   value="{{ $product->id }}">
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $product->category?->name ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-gray-600">
                                            Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <input type="number"
                                                   name="items[{{ $index }}][quantity]"
                                                   value="{{ old('items.' . $index . '.quantity', 0) }}"
                                                   min="0"
                                                   step="0.01"
                                                   data-cost-input="cost-{{ $index }}"
                                                   class="purchase-quantity w-28 rounded-lg border-gray-300 text-right text-sm focus:border-gray-900 focus:ring-gray-900">
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <input type="number"
                                                   id="cost-{{ $index }}"
                                                   name="items[{{ $index }}][unit_cost]"
                                                   value="{{ old('items.' . $index . '.unit_cost', $product->cost_price) }}"
                                                   min="0"
                                                   step="0.01"
                                                   class="purchase-cost w-36 rounded-lg border-gray-300 text-right text-sm focus:border-gray-900 focus:ring-gray-900">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                        <h2 class="font-semibold text-gray-900">Additional Info</h2>

                        <div>
                            <label for="discount_amount" class="block text-sm font-medium text-gray-700">
                                Discount
                            </label>

                            <input type="number"
                                   id="discount_amount"
                                   name="discount_amount"
                                   value="{{ old('discount_amount', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>

                        <div>
                            <label for="tax_amount" class="block text-sm font-medium text-gray-700">
                                Tax
                            </label>

                            <input type="number"
                                   id="tax_amount"
                                   name="tax_amount"
                                   value="{{ old('tax_amount', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="update_cost_price"
                                   value="1"
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                   @checked(old('update_cost_price', true))>

                            <span class="text-sm text-gray-700">
                                Update product cost price from this purchase
                            </span>
                        </label>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>

                            <textarea id="notes"
                                      name="notes"
                                      rows="4"
                                      class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="font-semibold text-gray-900">Summary</h2>

                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estimated Subtotal</span>
                                <span id="subtotalText" class="font-semibold text-gray-900">Rp 0</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount</span>
                                <span id="discountText" class="font-semibold text-gray-900">Rp 0</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span id="taxText" class="font-semibold text-gray-900">Rp 0</span>
                            </div>

                            <div class="flex justify-between border-t border-gray-200 pt-3 text-base">
                                <span class="font-bold text-gray-900">Estimated Total</span>
                                <span id="totalText" class="font-bold text-gray-900">Rp 0</span>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('admin.purchases.index') }}"
                               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Save Purchase
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const quantityInputs = document.querySelectorAll('.purchase-quantity');
        const costInputs = document.querySelectorAll('.purchase-cost');

        const discountAmount = document.getElementById('discount_amount');
        const taxAmount = document.getElementById('tax_amount');

        const subtotalText = document.getElementById('subtotalText');
        const discountText = document.getElementById('discountText');
        const taxText = document.getElementById('taxText');
        const totalText = document.getElementById('totalText');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        }

        function calculateTotal() {
            let subtotal = 0;

            quantityInputs.forEach((quantityInput, index) => {
                const quantity = Number(quantityInput.value || 0);
                const cost = Number(costInputs[index].value || 0);

                subtotal += quantity * cost;
            });

            const discount = Number(discountAmount.value || 0);
            const tax = Number(taxAmount.value || 0);
            const total = Math.max(subtotal - discount + tax, 0);

            subtotalText.textContent = formatRupiah(subtotal);
            discountText.textContent = formatRupiah(discount);
            taxText.textContent = formatRupiah(tax);
            totalText.textContent = formatRupiah(total);
        }

        quantityInputs.forEach(input => input.addEventListener('input', calculateTotal));
        costInputs.forEach(input => input.addEventListener('input', calculateTotal));
        discountAmount.addEventListener('input', calculateTotal);
        taxAmount.addEventListener('input', calculateTotal);

        calculateTotal();
    </script>
</x-layouts.app>