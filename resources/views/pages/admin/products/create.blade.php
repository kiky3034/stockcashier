<x-layouts.app :title="__('Create Product')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Add Product"
            description="Tambahkan produk baru, harga, gambar, dan stok awal per warehouse."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.products.index') }}" variant="secondary">
                    Back to Products
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              id="productCreateForm"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Simpan produk baru?"
              data-confirm-text="Produk akan ditambahkan beserta stok awal per warehouse."
              data-confirm-button="Ya, simpan"
              data-confirm-icon="question">
            @csrf

            <div class="space-y-6">
                <x-ui.card>
                    <div class="mb-5 flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Basic Information</h2>
                            <p class="mt-1 text-sm text-slate-500">Data utama produk yang akan tampil di POS dan inventory.</p>
                        </div>

                        <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                            New Product
                        </span>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100 py-2"
                                   placeholder="Nama produk"
                                   autofocus>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sku" class="block text-sm font-semibold text-slate-700">SKU</label>
                            <input type="text"
                                   id="sku"
                                   name="sku"
                                   value="{{ old('sku') }}"
                                   placeholder="Contoh: PRD-001"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm uppercase shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="barcode" class="block text-sm font-semibold text-slate-700">Barcode</label>
                            <input type="text"
                                   id="barcode"
                                   name="barcode"
                                   value="{{ old('barcode') }}"
                                   placeholder="Scan atau isi manual"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Scanner barcode biasanya membaca kode seperti input keyboard.
                            </p>
                            @error('barcode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700">Category</label>
                            <select id="category_id"
                                    name="category_id"
                                    class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                <option value="">- No Category -</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
                                    class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                <option value="">- No Unit -</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" @selected(old('unit_id') == $unit->id)>
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
                                    class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                <option value="">- No Supplier -</option>
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
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="mb-5 border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900">Pricing & Stock Alert</h2>
                        <p class="mt-1 text-sm text-slate-500">Atur harga modal, harga jual, dan batas peringatan stok.</p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <label for="cost_price" class="block text-sm font-semibold text-slate-700">Cost Price</label>
                            <input type="number"
                                   id="cost_price"
                                   name="cost_price"
                                   value="{{ old('cost_price', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            @error('cost_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block text-sm font-semibold text-slate-700">Selling Price</label>
                            <input type="number"
                                   id="selling_price"
                                   name="selling_price"
                                   value="{{ old('selling_price', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            @error('selling_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock_alert_level" class="block text-sm font-semibold text-slate-700">Stock Alert Level</label>
                            <input type="number"
                                   id="stock_alert_level"
                                   name="stock_alert_level"
                                   value="{{ old('stock_alert_level', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                            @error('stock_alert_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="mb-5 border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900">Initial Stock</h2>
                        <p class="py-2 mt-1 text-sm text-slate-500">Isi stok awal untuk setiap warehouse.</p>
                    </div>

                    <div id="initialStockSection" class="grid gap-4 md:grid-cols-2">
                        @foreach ($warehouses as $warehouse)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <label class="block text-sm font-semibold text-slate-700">
                                    {{ $warehouse->name }}
                                </label>
                                <p class="mt-1 text-xs text-slate-500">Code: {{ $warehouse->code }}</p>
                                <input type="number"
                                       name="stocks[{{ $warehouse->id }}]"
                                       value="{{ old('stocks.' . $warehouse->id, 0) }}"
                                       min="0"
                                       step="0.01"
                                       class="py-2 mt-3 block w-full rounded-2xl border-slate-200 bg-white text-sm shadow-sm transition focus:border-sky-400 focus:ring-sky-100">
                                @error('stocks.' . $warehouse->id)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Deskripsi produk, opsional"
                              class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Product Image</h2>
                        <p class="mt-1 text-sm text-slate-500">Upload gambar produk untuk POS.</p>
                    </div>

                    <div id="imagePreview" class="flex aspect-square items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-slate-50 text-sm font-medium text-slate-400">
                        No Image
                    </div>

                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="mt-4 block w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm file:mr-3 file:rounded-xl file:border-0 file:bg-sky-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-sky-700 hover:file:bg-sky-100">

                    <p class="mt-2 text-xs leading-5 text-slate-500">
                        Format: JPG, PNG, WEBP. Maksimal 2MB.
                    </p>

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-ui.card>

                <x-ui.card>
                    <h2 class="text-lg font-bold text-slate-900">Product Status</h2>
                    <p class="mt-1 text-sm text-slate-500">Atur status produk sebelum disimpan.</p>

                    <div class="mt-5 space-y-3">
                        <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <input type="checkbox"
                                   name="track_stock"
                                   id="trackStock"
                                   value="1"
                                   class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                                   @checked(old('track_stock', true))>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Track stock</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">Aktifkan jika stok produk perlu dipantau per warehouse.</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <input type="checkbox"
                                   name="is_active"
                                   id="isActive"
                                   value="1"
                                   class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                                   @checked(old('is_active', true))>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Active</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">Produk aktif akan tampil di POS dan laporan.</span>
                            </span>
                        </label>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="space-y-3">
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Save Product
                        </button>

                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Cancel
                        </a>
                    </div>
                </x-ui.card>
            </aside>
        </form>
    </div>

    <script>
        function productToast(icon, title) {
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
                    timerProgressBar: true,
                });
            }
        }

        const skuInput = document.getElementById('sku');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const costPriceInput = document.getElementById('cost_price');
        const sellingPriceInput = document.getElementById('selling_price');
        const trackStockInput = document.getElementById('trackStock');
        const initialStockSection = document.getElementById('initialStockSection');

        if (skuInput) {
            skuInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });
        }

        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function () {
                const file = this.files?.[0];

                if (!file) {
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    productToast('warning', 'Ukuran gambar lebih dari 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview" class="h-full w-full rounded-3xl object-cover">`;
                };
                reader.readAsDataURL(file);

                productToast('info', 'Gambar produk dipilih.');
            });
        }

        function checkPriceMargin() {
            const cost = Number(costPriceInput?.value || 0);
            const selling = Number(sellingPriceInput?.value || 0);

            if (selling > 0 && cost > 0 && selling <= cost) {
                productToast('warning', 'Harga jual kurang atau sama dengan harga modal.');
            }
        }

        if (costPriceInput && sellingPriceInput) {
            costPriceInput.addEventListener('change', checkPriceMargin);
            sellingPriceInput.addEventListener('change', checkPriceMargin);
        }

        function syncTrackStockState() {
            if (!trackStockInput || !initialStockSection) {
                return;
            }

            initialStockSection.classList.toggle('opacity-50', !trackStockInput.checked);
            initialStockSection.classList.toggle('pointer-events-none', !trackStockInput.checked);
        }

        if (trackStockInput) {
            trackStockInput.addEventListener('change', function () {
                syncTrackStockState();
                productToast(
                    this.checked ? 'info' : 'warning',
                    this.checked ? 'Tracking stok dinyalakan.' : 'Tracking stok dimatikan.'
                );
            });
            syncTrackStockState();
        }
    </script>
</x-layouts.app>
