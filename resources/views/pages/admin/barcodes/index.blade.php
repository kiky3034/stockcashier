<x-layouts.app :title="__('Barcode Generator')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Barcode Generator"
            description="Pilih produk dan jumlah label, lalu cetak stiker barcode secara massal."
        >
            <x-slot:actions>
                <button type="button"
                        id="printBarcodesButton"
                        onclick="printBarcodes()"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-sky-100 disabled:opacity-50"
                        disabled>
                    🖨️ Print Labels
                </button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        {{-- Search --}}
        <x-ui.card>
            <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700">Search Product</label>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari berdasarkan nama, SKU, atau barcode..."
                           class="mt-2 w-full rounded-2xl border border-slate-300 bg-white py-3 px-4 text-sm shadow-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('admin.barcodes.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-ui.card>

        {{-- Product Selection --}}
        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Select Products</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Centang produk yang ingin dicetak label barcodenya. Set jumlah label per produk.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700" id="selectedCountBadge">
                        0 selected
                    </span>
                    <button type="button"
                            onclick="toggleSelectAll()"
                            class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                        Select All
                    </button>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <label class="flex cursor-pointer items-center gap-4 px-5 py-4 transition hover:bg-sky-50/40"
                           id="product-row-{{ $product->id }}">
                        <input type="checkbox"
                               class="product-checkbox h-5 w-5 rounded-lg border-slate-300 text-sky-500 shadow-sm focus:ring-sky-200"
                               data-product-id="{{ $product->id }}"
                               data-product-name="{{ $product->name }}"
                               data-product-sku="{{ $product->sku }}"
                               data-product-barcode="{{ $product->barcode }}"
                               data-product-price="{{ number_format($product->selling_price, 0, ',', '.') }}"
                               onchange="updateSelection()">

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-900">{{ $product->name }}</span>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-mono text-slate-600">{{ $product->sku }}</span>
                                @if($product->barcode)
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-mono text-emerald-700">{{ $product->barcode }}</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-600">No barcode</span>
                                @endif
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                <span>{{ $product->category?->name ?? 'Uncategorized' }}</span>
                                <span>·</span>
                                <span class="font-semibold text-slate-700">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                            <label class="text-xs font-semibold text-slate-500">Qty:</label>
                            <input type="number"
                                   class="label-qty w-16 rounded-xl border border-slate-300 bg-white py-2 px-3 text-center text-sm font-bold shadow-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-100"
                                   data-product-id="{{ $product->id }}"
                                   value="1"
                                   min="1"
                                   max="100"
                                   onchange="updateSelection()">
                        </div>
                    </label>
                @empty
                    <div class="p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-500">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                        <div class="mt-4 font-semibold text-slate-900">Tidak ada produk ditemukan</div>
                        <p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci pencarian.</p>
                    </div>
                @endforelse
            </div>
        </x-ui.card>

        {{-- Print Preview Section --}}
        <x-ui.card padding="p-0" class="overflow-hidden" id="previewSection" style="display: none;">
            <div class="border-b border-slate-100 bg-gradient-to-r from-sky-50 to-cyan-50 p-5">
                <h2 class="text-lg font-black text-slate-900">Print Preview</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Preview label barcode sebelum dicetak. Layout dioptimalkan untuk kertas A4.
                </p>
            </div>

            <div class="p-5">
                <div id="barcodePreview" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4"></div>
            </div>
        </x-ui.card>
    </div>

    {{-- Hidden print area --}}
    <div id="printArea" class="hidden print:block"></div>

    {{-- JsBarcode CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <style>
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { visibility: visible; }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .barcode-label {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>

    <script>
        function updateSelection() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const count = checkboxes.length;
            const badge = document.getElementById('selectedCountBadge');
            const printBtn = document.getElementById('printBarcodesButton');
            const previewSection = document.getElementById('previewSection');

            badge.textContent = `${count} selected`;
            printBtn.disabled = count === 0;

            if (count > 0) {
                previewSection.style.display = '';
                renderPreview();
            } else {
                previewSection.style.display = 'none';
            }
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => cb.checked = !allChecked);
            updateSelection();
        }

        function getSelectedProducts() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const products = [];

            checkboxes.forEach(cb => {
                const productId = cb.dataset.productId;
                const qtyInput = document.querySelector(`.label-qty[data-product-id="${productId}"]`);
                const qty = Math.max(1, Math.min(100, parseInt(qtyInput?.value || 1)));

                products.push({
                    id: productId,
                    name: cb.dataset.productName,
                    sku: cb.dataset.productSku,
                    barcode: cb.dataset.productBarcode || cb.dataset.productSku,
                    price: cb.dataset.productPrice,
                    qty: qty,
                });
            });

            return products;
        }

        function renderPreview() {
            const products = getSelectedProducts();
            const container = document.getElementById('barcodePreview');
            container.innerHTML = '';

            products.forEach(product => {
                for (let i = 0; i < Math.min(product.qty, 10); i++) {
                    const label = createLabelElement(product);
                    container.appendChild(label);
                }
                if (product.qty > 10) {
                    const moreEl = document.createElement('div');
                    moreEl.className = 'flex items-center justify-center rounded-2xl border-2 border-dashed border-sky-200 bg-sky-50/50 p-4 text-sm font-semibold text-sky-600';
                    moreEl.textContent = `+${product.qty - 10} more labels`;
                    container.appendChild(moreEl);
                }
            });
        }

        function createLabelElement(product) {
            const label = document.createElement('div');
            label.className = 'barcode-label rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm';

            const nameEl = document.createElement('div');
            nameEl.className = 'truncate text-xs font-bold text-slate-900';
            nameEl.textContent = product.name;
            label.appendChild(nameEl);

            const canvas = document.createElement('canvas');
            canvas.className = 'mx-auto mt-1';
            label.appendChild(canvas);

            try {
                JsBarcode(canvas, product.barcode, {
                    format: 'CODE128',
                    width: 1.5,
                    height: 40,
                    displayValue: true,
                    fontSize: 11,
                    font: 'monospace',
                    margin: 2,
                    textMargin: 2,
                });
            } catch (e) {
                canvas.style.display = 'none';
                const errorEl = document.createElement('div');
                errorEl.className = 'mt-2 rounded-lg bg-red-50 p-2 text-xs text-red-600';
                errorEl.textContent = 'Invalid barcode value';
                label.appendChild(errorEl);
            }

            const priceEl = document.createElement('div');
            priceEl.className = 'mt-1 text-xs font-bold text-sky-700';
            priceEl.textContent = `Rp ${product.price}`;
            label.appendChild(priceEl);

            return label;
        }

        function printBarcodes() {
            const products = getSelectedProducts();
            if (products.length === 0) return;

            const printArea = document.getElementById('printArea');
            printArea.innerHTML = '';
            printArea.className = '';

            const style = document.createElement('style');
            style.textContent = `
                .print-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 8px;
                    padding: 8px;
                }
                .barcode-label {
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 8px;
                    text-align: center;
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                .barcode-label .label-name {
                    font-size: 10px;
                    font-weight: bold;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .barcode-label .label-price {
                    font-size: 10px;
                    font-weight: bold;
                    color: #0369a1;
                    margin-top: 2px;
                }
            `;
            printArea.appendChild(style);

            const grid = document.createElement('div');
            grid.className = 'print-grid';

            products.forEach(product => {
                for (let i = 0; i < product.qty; i++) {
                    const label = document.createElement('div');
                    label.className = 'barcode-label';

                    const nameEl = document.createElement('div');
                    nameEl.className = 'label-name';
                    nameEl.textContent = product.name;
                    label.appendChild(nameEl);

                    const canvas = document.createElement('canvas');
                    label.appendChild(canvas);

                    try {
                        JsBarcode(canvas, product.barcode, {
                            format: 'CODE128',
                            width: 1.2,
                            height: 35,
                            displayValue: true,
                            fontSize: 10,
                            font: 'monospace',
                            margin: 1,
                            textMargin: 1,
                        });
                    } catch (e) {
                        const skuEl = document.createElement('div');
                        skuEl.style.cssText = 'font-family:monospace;font-size:12px;padding:8px;';
                        skuEl.textContent = product.sku;
                        label.appendChild(skuEl);
                    }

                    const priceEl = document.createElement('div');
                    priceEl.className = 'label-price';
                    priceEl.textContent = `Rp ${product.price}`;
                    label.appendChild(priceEl);

                    grid.appendChild(label);
                }
            });

            printArea.appendChild(grid);

            setTimeout(() => {
                window.print();
                // Reset after print
                setTimeout(() => {
                    printArea.innerHTML = '';
                    printArea.className = 'hidden print:block';
                }, 500);
            }, 300);
        }
    </script>
</x-layouts.app>
