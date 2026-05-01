<x-layouts.app :title="__('Create Purchase')">
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-6xl space-y-6">
            <x-page-header
                title="New Purchase"
                description="Input barang masuk dari supplier dan update stok warehouse tujuan."
            >
                <x-slot:actions>
                    <x-ui.link-button href="{{ route('admin.purchases.index') }}" variant="secondary">
                        Back
                    </x-ui.link-button>
                </x-slot:actions>
            </x-page-header>

            <x-flash-message />

            <form method="POST"
                  action="{{ route('admin.purchases.store') }}"
                  id="purchaseForm"
                  class="space-y-6"
                  data-confirm-submit
                  data-confirm-title="Simpan purchase?"
                  data-confirm-text="Purchase akan disimpan dan stok produk akan bertambah sesuai quantity yang diisi."
                  data-confirm-button="Ya, simpan purchase"
                  data-confirm-icon="question">
                @csrf

                <x-ui.card>
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-slate-900">Purchase Information</h2>
                            <p class="mt-1 text-sm text-slate-500">Pilih supplier dan warehouse tujuan barang masuk.</p>
                        </div>

                        <div class="hidden rounded-2xl bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 ring-1 ring-sky-100 sm:block">
                            Stock In
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="supplier_id" class="block text-sm font-semibold text-slate-700">
                                Supplier
                            </label>

                            <select id="supplier_id"
                                    name="supplier_id"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
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
                            <label for="warehouse_id" class="block text-sm font-semibold text-slate-700">
                                Warehouse Destination
                            </label>

                            <select id="warehouse_id"
                                    name="warehouse_id"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
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
                </x-ui.card>

                <x-ui.card padding="p-0">
                    <div class="border-b border-slate-100 p-4 sm:p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="font-bold text-slate-900">Purchase Items</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Isi quantity lebih dari 0 untuk produk yang dibeli.
                                </p>
                            </div>

                            <div class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                {{ $products->count() }} Products
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Product</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Category</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Current Cost</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Quantity</th>
                                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Unit Cost</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($products as $index => $product)
                                    <tr class="transition hover:bg-sky-50/40">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-900">
                                                {{ $product->name }}
                                            </div>

                                            <div class="mt-1 text-xs text-slate-500">
                                                SKU: {{ $product->sku }}
                                                @if ($product->unit)
                                                    · Unit: {{ $product->unit->abbreviation }}
                                                @endif
                                            </div>

                                            <input type="hidden"
                                                   name="items[{{ $index }}][product_id]"
                                                   value="{{ $product->id }}">
                                        </td>

                                        <td class="px-4 py-3 text-slate-600">
                                            {{ $product->category?->name ?? '-' }}
                                        </td>

                                        <td class="px-4 py-3 text-right text-slate-600">
                                            Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <input type="number"
                                                   name="items[{{ $index }}][quantity]"
                                                   value="{{ old('items.' . $index . '.quantity', 0) }}"
                                                   min="0"
                                                   step="0.01"
                                                   data-cost-input="cost-{{ $index }}"
                                                   class="py-2 lg:w-14 purchase-quantity w-28 rounded-xl border-slate-200 bg-slate-50 text-right text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                        </td>

                                        <td class="px-4 py-3 text-right">
                                            <input type="number"
                                                   id="cost-{{ $index }}"
                                                   name="items[{{ $index }}][unit_cost]"
                                                   value="{{ old('items.' . $index . '.unit_cost', $product->cost_price) }}"
                                                   min="0"
                                                   step="0.01"
                                                   class="py-2 lg:w-28 purchase-cost w-36 rounded-xl border-slate-200 bg-slate-50 text-right text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>

                <div class="grid gap-6 md:grid-cols-2">
                    <x-ui.card>
                        <h2 class="font-bold text-slate-900">Additional Info</h2>
                        <p class="mt-1 text-sm text-slate-500">Tambahkan diskon, pajak, catatan, dan opsi update harga modal.</p>

                        <div class="mt-5 space-y-4">
                            <div>
                                <label for="discount_amount" class="block text-sm font-semibold text-slate-700">
                                    Discount
                                </label>

                                <input type="number"
                                       id="discount_amount"
                                       name="discount_amount"
                                       value="{{ old('discount_amount', 0) }}"
                                       min="0"
                                       step="0.01"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            </div>

                            <div>
                                <label for="tax_amount" class="block text-sm font-semibold text-slate-700">
                                    Tax
                                </label>

                                <input type="number"
                                       id="tax_amount"
                                       name="tax_amount"
                                       value="{{ old('tax_amount', 0) }}"
                                       min="0"
                                       step="0.01"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            </div>

                            <label class="flex items-start gap-3 rounded-2xl border border-sky-100 bg-sky-50 p-4">
                                <input type="checkbox"
                                       name="update_cost_price"
                                       value="1"
                                       class="mt-0.5 rounded border-sky-300 text-sky-500 focus:ring-sky-100"
                                       @checked(old('update_cost_price', true))>

                                <span class="text-sm text-sky-800">
                                    <span class="font-semibold">Update product cost price from this purchase</span>
                                    <span class="mt-1 block text-xs text-sky-700/80">
                                        Cost price produk akan mengikuti harga pembelian yang diinput.
                                    </span>
                                </span>
                            </label>

                            <div>
                                <label for="notes" class="block text-sm font-semibold text-slate-700">
                                    Notes
                                </label>

                                <textarea id="notes"
                                          name="notes"
                                          rows="4"
                                          class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <h2 class="font-bold text-slate-900">Summary</h2>
                        <p class="mt-1 text-sm text-slate-500">Estimasi total akan berubah otomatis sesuai item yang diisi.</p>

                        <div class="mt-5 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Estimated Subtotal</span>
                                <span id="subtotalText" class="font-semibold text-slate-900">Rp 0</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500">Discount</span>
                                <span id="discountText" class="font-semibold text-slate-900">Rp 0</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-slate-500">Tax</span>
                                <span id="taxText" class="font-semibold text-slate-900">Rp 0</span>
                            </div>

                            <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-cyan-50 p-4 ring-1 ring-sky-100">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="font-bold text-slate-900">Estimated Total</span>
                                    <span id="totalText" class="text-xl font-black text-sky-700">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('admin.purchases.index') }}"
                               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                Save Purchase
                            </button>
                        </div>
                    </x-ui.card>
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

        function showToast(icon, title) {
            if (window.Toast) {
                Toast.fire({
                    icon: icon,
                    title: title
                });

                return;
            }

            if (window.Swal) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    timer: 2200,
                    showConfirmButton: false
                });
            }
        }

        function selectedItemCount() {
            return Array.from(quantityInputs).filter(input => Number(input.value || 0) > 0).length;
        }

        const purchaseForm = document.getElementById('purchaseForm');

        purchaseForm.addEventListener('submit', function (event) {
            if (selectedItemCount() === 0) {
                event.preventDefault();
                event.stopImmediatePropagation();

                showToast('warning', 'Isi minimal 1 produk dengan quantity lebih dari 0.');

                const firstQuantityInput = document.querySelector('.purchase-quantity');
                firstQuantityInput?.focus();

                return false;
            }
        });

        quantityInputs.forEach(input => input.addEventListener('input', calculateTotal));
        costInputs.forEach(input => input.addEventListener('input', calculateTotal));
        discountAmount.addEventListener('input', calculateTotal);
        taxAmount.addEventListener('input', calculateTotal);

        calculateTotal();
    </script>
</x-layouts.app>
