<x-layouts.app :title="__('Edit Product')">
    <div class="p-6">
        <div class="mx-auto max-w-4xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui data produk dan stok per warehouse.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $product->name) }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
                                   autofocus>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                            <input type="text"
                                   id="sku"
                                   name="sku"
                                   value="{{ old('sku', $product->sku) }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm uppercase focus:border-gray-900 focus:ring-gray-900">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                            <input type="text"
                                   id="barcode"
                                   name="barcode"
                                   value="{{ old('barcode', $product->barcode) }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('barcode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category_id"
                                    name="category_id"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">- No Category -</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                            <select id="unit_id"
                                    name="unit_id"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">- No Unit -</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id) == $unit->id)>
                                        {{ $unit->name }} ({{ $unit->abbreviation }})
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select id="supplier_id"
                                    name="supplier_id"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="">- No Supplier -</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product->supplier_id) == $supplier->id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cost_price" class="block text-sm font-medium text-gray-700">Cost Price</label>
                            <input type="number"
                                   id="cost_price"
                                   name="cost_price"
                                   value="{{ old('cost_price', $product->cost_price) }}"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('cost_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">Selling Price</label>
                            <input type="number"
                                   id="selling_price"
                                   name="selling_price"
                                   value="{{ old('selling_price', $product->selling_price) }}"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('selling_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock_alert_level" class="block text-sm font-medium text-gray-700">Stock Alert Level</label>
                            <input type="number"
                                   id="stock_alert_level"
                                   name="stock_alert_level"
                                   value="{{ old('stock_alert_level', $product->stock_alert_level) }}"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            @error('stock_alert_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4">
                        <div class="mb-4">
                            <h2 class="font-semibold text-gray-900">Stock per Warehouse</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Perbarui jumlah stok produk di setiap warehouse.
                            </p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ($warehouses as $warehouse)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ $warehouse->name }} ({{ $warehouse->code }})
                                    </label>
                                    <input type="number"
                                           name="stocks[{{ $warehouse->id }}]"
                                           value="{{ old('stocks.' . $warehouse->id, $stockQuantities[$warehouse->id] ?? 0) }}"
                                           min="0"
                                           step="0.01"
                                           class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                    @error('stocks.' . $warehouse->id)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="track_stock"
                                   value="1"
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                   @checked(old('track_stock', $product->track_stock))>
                            <span class="text-sm text-gray-700">Track stock for this product</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                   @checked(old('is_active', $product->is_active))>
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.products.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>