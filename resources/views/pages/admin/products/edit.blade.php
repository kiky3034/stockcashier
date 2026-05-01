<x-layouts.app :title="__('Edit Product')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Edit Product"
            description="Perbarui informasi produk, harga, gambar, status, dan stok per warehouse."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.products.index') }}" variant="secondary">
                    Back to Products
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.products.update', $product) }}"
              enctype="multipart/form-data"
              class="space-y-6"
              data-confirm-submit
              data-confirm-title="Update produk?"
              data-confirm-text="Perubahan data produk, harga, gambar, dan stok warehouse akan disimpan."
              data-confirm-button="Ya, update"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <x-ui.card>
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Product Information</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Data utama produk yang akan muncul di master product dan POS.
                                </p>
                            </div>

                            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                {{ $product->sku }}
                            </span>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $product->name) }}"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                       autofocus
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sku" class="block text-sm font-semibold text-slate-700">SKU</label>
                                <input type="text"
                                       id="sku"
                                       name="sku"
                                       value="{{ old('sku', $product->sku) }}"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm uppercase text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                @error('sku')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="barcode" class="block text-sm font-semibold text-slate-700">Barcode</label>
                                <input type="text"
                                       id="barcode"
                                       name="barcode"
                                       value="{{ old('barcode', $product->barcode) }}"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                <p class="mt-1 text-xs text-slate-500">
                                    Barcode bisa diisi manual atau dari kode barcode produk.
                                </p>
                                @error('barcode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-slate-700">Category</label>
                                <select id="category_id"
                                        name="category_id"
                                        class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
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
                                <label for="unit_id" class="block text-sm font-semibold text-slate-700">Unit</label>
                                <select id="unit_id"
                                        name="unit_id"
                                        class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
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

                            <div class="md:col-span-2">
                                <label for="supplier_id" class="block text-sm font-semibold text-slate-700">Supplier</label>
                                <select id="supplier_id"
                                        name="supplier_id"
                                        class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
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
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">Pricing & Stock Alert</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Atur harga modal, harga jual, dan batas minimal stok.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-3">
                            <div>
                                <label for="cost_price" class="block text-sm font-semibold text-slate-700">Cost Price</label>
                                <input type="number"
                                       id="cost_price"
                                       name="cost_price"
                                       value="{{ old('cost_price', $product->cost_price) }}"
                                       min="0"
                                       step="0.01"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                @error('cost_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="selling_price" class="block text-sm font-semibold text-slate-700">Selling Price</label>
                                <input type="number"
                                       id="selling_price"
                                       name="selling_price"
                                       value="{{ old('selling_price', $product->selling_price) }}"
                                       min="0"
                                       step="0.01"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                @error('selling_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="stock_alert_level" class="block text-sm font-semibold text-slate-700">Stock Alert Level</label>
                                <input type="number"
                                       id="stock_alert_level"
                                       name="stock_alert_level"
                                       value="{{ old('stock_alert_level', $product->stock_alert_level) }}"
                                       min="0"
                                       step="0.01"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                @error('stock_alert_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">Stock per Warehouse</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Perbarui jumlah stok produk di setiap warehouse.
                            </p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ($warehouses as $warehouse)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <label class="block text-sm font-semibold text-slate-700">
                                        {{ $warehouse->name }}
                                    </label>
                                    <p class="mt-1 text-xs text-slate-500">{{ $warehouse->code }}</p>
                                    <input type="number"
                                           name="stocks[{{ $warehouse->id }}]"
                                           value="{{ old('stocks.' . $warehouse->id, $stockQuantities[$warehouse->id] ?? 0) }}"
                                           min="0"
                                           step="0.01"
                                           class="mt-3 block w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                                    @error('stocks.' . $warehouse->id)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">Description</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Tambahkan catatan atau detail tambahan produk.
                            </p>
                        </div>

                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-ui.card>
                </div>

                <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                    <x-ui.card>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Product Image</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Gambar akan tampil di product list dan POS.
                            </p>
                        </div>

                        <div class="mt-5">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                     alt="{{ $product->name }}"
                                     class="h-52 w-full rounded-3xl border border-slate-200 object-cover shadow-sm">
                            @else
                                <div class="flex h-52 w-full items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 text-sm font-medium text-slate-400">
                                    No Image
                                </div>
                            @endif

                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   class="mt-4 block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-sky-700 hover:file:bg-sky-100">

                            <p class="mt-2 text-xs text-slate-500">
                                Kosongkan jika tidak ingin mengganti gambar. Maksimal 2MB.
                            </p>

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <h2 class="text-lg font-bold text-slate-900">Product Status</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Tentukan apakah produk aktif dan stoknya dilacak.
                        </p>

                        <div class="mt-5 space-y-3">
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox"
                                       name="track_stock"
                                       value="1"
                                       class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                       @checked(old('track_stock', $product->track_stock))>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-800">Track stock</span>
                                    <span class="mt-1 block text-xs text-slate-500">
                                        Stok produk akan berkurang saat transaksi POS.
                                    </span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                       @checked(old('is_active', $product->is_active))>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-800">Active</span>
                                    <span class="mt-1 block text-xs text-slate-500">
                                        Produk aktif akan muncul di POS dan daftar produk.
                                    </span>
                                </span>
                            </label>
                        </div>

                        @error('track_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-ui.card>

                    <x-ui.card>
                        <div class="space-y-3">
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                Update Product
                            </button>

                            <a href="{{ route('admin.products.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                Cancel
                            </a>
                        </div>
                    </x-ui.card>
                </aside>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            const trackStockInput = document.querySelector('input[name="track_stock"]');
            const costInput = document.querySelector('input[name="cost_price"]');
            const sellingInput = document.querySelector('input[name="selling_price"]');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        timer: 1800,
                        showConfirmButton: false
                    });
                }
            }

            if (imageInput) {
                imageInput.addEventListener('change', function () {
                    const file = imageInput.files?.[0];

                    if (!file) {
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        showToast('warning', 'Ukuran gambar lebih dari 2MB.');
                        return;
                    }

                    showToast('success', 'Gambar produk baru siap diupload.');
                });
            }

            if (trackStockInput) {
                trackStockInput.addEventListener('change', function () {
                    showToast(
                        trackStockInput.checked ? 'info' : 'warning',
                        trackStockInput.checked
                            ? 'Produk akan memakai tracking stok.'
                            : 'Produk tidak akan memakai tracking stok.'
                    );
                });
            }

            if (costInput && sellingInput) {
                sellingInput.addEventListener('blur', function () {
                    const cost = Number(costInput.value || 0);
                    const selling = Number(sellingInput.value || 0);

                    if (selling > 0 && cost > 0 && selling <= cost) {
                        showToast('warning', 'Harga jual sebaiknya lebih besar dari harga modal.');
                    }
                });
            }
        });
    </script>
</x-layouts.app>
