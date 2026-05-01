<x-layouts.app :title="__('Stock Adjustment')">
    <div class="p-6">
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Stock Adjustment</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Tambah atau kurangi stok secara manual.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST"
                      action="{{ route('admin.stock-adjustments.store') }}"
                      id="stockAdjustmentForm"
                      class="space-y-5"
                      data-confirm-submit
                      data-confirm-title="Simpan stock adjustment?"
                      data-confirm-text="Perubahan stok akan langsung disimpan dan tercatat di stock movements."
                      data-confirm-button="Ya, simpan adjustment"
                      data-confirm-icon="question">
                    @csrf

                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">
                            Product
                        </label>
                        <select id="product_id"
                                name="product_id"
                                required
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="">- Select Product -</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                    {{ $product->name }} — {{ $product->sku }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">
                            Warehouse
                        </label>
                        <select id="warehouse_id"
                                name="warehouse_id"
                                required
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

                    <div>
                        <label for="direction" class="block text-sm font-medium text-gray-700">
                            Direction
                        </label>
                        <select id="direction"
                                name="direction"
                                required
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="in" @selected(old('direction') === 'in')>Stock In / Tambah Stok</option>
                            <option value="out" @selected(old('direction') === 'out')>Stock Out / Kurangi Stok</option>
                        </select>

                        @error('direction')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">
                            Quantity
                        </label>
                        <input type="number"
                               id="quantity"
                               name="quantity"
                               value="{{ old('quantity') }}"
                               required
                               min="0.01"
                               step="0.01"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Notes
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="4"
                                  placeholder="Contoh: Koreksi stok karena stock opname"
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.stocks.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Save Adjustment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const direction = document.getElementById('direction');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            if (direction) {
                direction.addEventListener('change', function () {
                    if (direction.value === 'out') {
                        showToast('warning', 'Pastikan stok mencukupi sebelum stock out.');
                    }

                    if (direction.value === 'in') {
                        showToast('info', 'Stock in akan menambah stok warehouse yang dipilih.');
                    }
                });
            }
        });
    </script></x-layouts.app>