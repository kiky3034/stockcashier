<x-layouts.app :title="__('Stock Adjustment')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Stock Adjustment"
            description="Tambah atau kurangi stok secara manual dan tercatat otomatis di stock movements."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.stocks.index') }}" variant="secondary">
                    Back to Stocks
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('admin.stock-movements.index') }}" variant="ghost">
                    Movement History
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-100 bg-gradient-to-r from-sky-50 to-cyan-50 p-5 sm:p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm ring-1 ring-sky-100">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Adjustment Form</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                Pilih produk, warehouse, direction, lalu isi quantity adjustment.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('admin.stock-adjustments.store') }}"
                      id="stockAdjustmentForm"
                      class="space-y-6 p-5 sm:p-6"
                      data-confirm-submit
                      data-confirm-title="Simpan stock adjustment?"
                      data-confirm-text="Perubahan stok akan langsung disimpan dan tercatat di stock movements."
                      data-confirm-button="Ya, simpan adjustment"
                      data-confirm-icon="question">
                    @csrf

                    <div class="grid gap-5 lg:grid-cols-2">
                        <div class="lg:col-span-2">
                            <label for="product_id" class="block text-sm font-semibold text-slate-700">
                                Product
                            </label>
                            <select id="product_id"
                                    name="product_id"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                <option value="">- Select Product -</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        {{ $product->name }} — {{ $product->sku }}
                                    </option>
                                @endforeach
                            </select>

                            @error('product_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="warehouse_id" class="block text-sm font-semibold text-slate-700">
                                Warehouse
                            </label>
                            <select id="warehouse_id"
                                    name="warehouse_id"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                <option value="">- Select Warehouse -</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                        {{ $warehouse->name }} — {{ $warehouse->code }}
                                    </option>
                                @endforeach
                            </select>

                            @error('warehouse_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="direction" class="block text-sm font-semibold text-slate-700">
                                Direction
                            </label>
                            <select id="direction"
                                    name="direction"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                <option value="in" @selected(old('direction') === 'in')>Stock In / Tambah Stok</option>
                                <option value="out" @selected(old('direction') === 'out')>Stock Out / Kurangi Stok</option>
                            </select>

                            @error('direction')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-slate-700">
                                Quantity
                            </label>
                            <input type="number"
                                   id="quantity"
                                   name="quantity"
                                   value="{{ old('quantity') }}"
                                   required
                                   min="0.01"
                                   step="0.01"
                                   placeholder="0.00"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            @error('quantity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label for="notes" class="block text-sm font-semibold text-slate-700">
                                Notes
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="4"
                                      placeholder="Contoh: Koreksi stok karena stock opname"
                                      class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">{{ old('notes') }}</textarea>

                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                        <x-ui.link-button href="{{ route('admin.stocks.index') }}" variant="secondary">
                            Cancel
                        </x-ui.link-button>

                        <x-ui.button-primary type="submit">
                            Save Adjustment
                        </x-ui.button-primary>
                    </div>
                </form>
            </x-ui.card>

            <div class="space-y-6">
                <x-ui.card>
                    <div class="flex items-start gap-4">
                        <div id="directionIcon" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-900">Adjustment Impact</h2>
                            <p id="directionHint" class="mt-1 text-sm leading-6 text-slate-500">
                                Stock in akan menambah stok warehouse yang dipilih.
                            </p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Checklist</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-50 text-xs font-bold text-sky-600 ring-1 ring-sky-100">1</span>
                            <p>Pastikan produk dan warehouse sudah benar.</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-50 text-xs font-bold text-sky-600 ring-1 ring-sky-100">2</span>
                            <p>Gunakan stock out hanya untuk koreksi stok keluar.</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-50 text-xs font-bold text-sky-600 ring-1 ring-sky-100">3</span>
                            <p>Isi notes agar audit log lebih mudah dibaca.</p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Quick Links</h2>
                    <div class="mt-4 grid gap-2">
                        <a href="{{ route('admin.stocks.index') }}"
                           class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                            View Stocks
                        </a>
                        <a href="{{ route('admin.stock-movements.index') }}"
                           class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                            View Stock Movements
                        </a>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const direction = document.getElementById('direction');
            const quantity = document.getElementById('quantity');
            const directionHint = document.getElementById('directionHint');
            const directionIcon = document.getElementById('directionIcon');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            function updateDirectionUi(showNotification = false) {
                if (! direction) {
                    return;
                }

                if (direction.value === 'out') {
                    directionHint.textContent = 'Stock out akan mengurangi stok warehouse. Pastikan stok mencukupi sebelum menyimpan.';
                    directionIcon.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-100';

                    if (showNotification) {
                        showToast('warning', 'Pastikan stok mencukupi sebelum stock out.');
                    }

                    return;
                }

                directionHint.textContent = 'Stock in akan menambah stok warehouse yang dipilih.';
                directionIcon.className = 'flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100';

                if (showNotification) {
                    showToast('info', 'Stock in akan menambah stok warehouse yang dipilih.');
                }
            }

            if (direction) {
                direction.addEventListener('change', function () {
                    updateDirectionUi(true);
                });

                updateDirectionUi(false);
            }

            if (quantity) {
                quantity.addEventListener('input', function () {
                    if (Number(quantity.value || 0) <= 0) {
                        return;
                    }

                    if (Number(quantity.value || 0) >= 1000) {
                        showToast('warning', 'Quantity adjustment cukup besar, pastikan angkanya benar.');
                    }
                }, { once: true });
            }
        });
    </script>
</x-layouts.app>
