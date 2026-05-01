<x-layouts.app :title="__('POS Cashier')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="POS Cashier"
            description="Buat transaksi penjualan lebih cepat dengan scan barcode, pencarian produk, dan shortcut keyboard."
        >
            <x-slot:actions>
                <button type="button"
                        id="posShortcutHelpButton"
                        class="inline-flex items-center justify-center rounded-xl border border-sky-200 bg-white px-4 py-2.5 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-50 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    Shortcut
                </button>

                <x-ui.link-button href="{{ route('cashier.sales.index') }}" variant="secondary">
                    Sales History
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST" action="{{ route('cashier.pos.store') }}" id="posForm">
            @csrf

            <div id="itemsInputContainer"></div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
                <div class="space-y-5">
                    <div class="overflow-hidden rounded-3xl border border-sky-100 bg-white shadow-sm">
                        <div class="bg-gradient-to-r from-sky-500 via-sky-400 to-cyan-400 p-5 text-white">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <div class="text-sm font-semibold uppercase tracking-wide text-sky-50">
                                        Fast Checkout
                                    </div>
                                    <h2 class="mt-1 text-xl font-black tracking-tight">
                                        Scan, pilih produk, lalu complete sale.
                                    </h2>
                                </div>

                                <div class="rounded-2xl bg-white/15 px-3 py-2 text-xs font-semibold text-white ring-1 ring-white/20">
                                    F2 Search · F4 Scan · F9 Bayar · Ctrl+Enter Selesai
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 p-5 md:grid-cols-3">
                            <div>
                                <label class="block text-center text-sm font-semibold text-slate-700">Warehouse</label>
                                <div class="relative mt-2">
                                    <select name="warehouse_id"
                                            id="warehouseId"
                                            style="-webkit-appearance:none;-moz-appearance:none;appearance:none;"
                                            class="w-full rounded-2xl border-2 border-amber-400 bg-amber-50/90 py-3 px-4 text-sm font-semibold text-slate-800 shadow-md transition focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-200">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->name }} — {{ $warehouse->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-center text-sm font-semibold text-slate-700">
                                    Scan Barcode
                                </label>
                                <div class="relative mt-2">
                                    <input type="text"
                                        id="barcodeInput"
                                        placeholder="Barcode"
                                        autocomplete="off"
                                        class="w-full rounded-2xl border-2 border-emerald-400 bg-emerald-50/90 py-3 px-4 text-sm font-semibold text-slate-800 shadow-md transition placeholder:text-emerald-500 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-center text-sm font-semibold text-slate-700">
                                    Search Product
                                </label>
                                <div class="relative mt-2">
                                    <input type="text"
                                        id="productSearch"
                                        placeholder="Search"
                                        autocomplete="off"
                                        class="w-full rounded-2xl border-2 border-sky-400 bg-sky-50/90 py-3 px-4 text-sm font-medium text-slate-800 shadow-md transition placeholder:text-sky-500 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui.card padding="p-0" class="overflow-hidden">
                        <div class="flex flex-col gap-3 border-b border-slate-100 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-black text-slate-900">Products</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Pilih produk atau scan barcode untuk menambahkan ke cart.
                                </p>
                            </div>

                            <div class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700">
                                Warehouse-aware stock
                            </div>
                        </div>

                        <div class="p-5">
                            <div id="productGrid" class="grid gap-4 sm:grid-cols-2 2xl:grid-cols-3"></div>
                        </div>
                    </x-ui.card>
                </div>

                <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
                    <x-ui.card padding="p-0" class="overflow-hidden">
                        <div class="border-b border-slate-100 bg-gradient-to-r from-sky-50 to-cyan-50 p-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-black text-slate-900">Cart</h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Review item sebelum pembayaran.
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-sky-600 shadow-sm">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="8" cy="21" r="1"></circle>
                                        <circle cx="19" cy="21" r="1"></circle>
                                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L22 7H5.12"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="max-h-[42vh] space-y-3 overflow-y-auto p-5 xl:max-h-[40vh]" id="cartItems"></div>

                        <div class="space-y-4 border-t border-slate-100 bg-slate-50/60 p-5">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-500">Subtotal</span>
                                <span class="font-bold text-slate-900" id="subtotalText">Rp 0</span>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">Discount</label>
                                    <input type="number"
                                           name="discount_amount"
                                           id="discountAmount"
                                           value="0"
                                           min="0"
                                           step="0.01"
                                           class="mt-2 w-full rounded-2xl border border-slate-300 bg-white py-3 px-4 text-sm shadow-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">Tax</label>
                                    <input type="number"
                                           name="tax_amount"
                                           id="taxAmount"
                                           value="0"
                                           min="0"
                                           step="0.01"
                                           class="mt-2 w-full rounded-2xl border border-slate-300 bg-white py-3 px-4 text-sm shadow-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                                </div>
                            </div>

                            <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                                <div class="flex justify-between text-sm text-slate-500">
                                    <span>Total</span>
                                    <span>Due</span>
                                </div>
                                <div class="mt-1 flex justify-between gap-4 text-xl font-black text-slate-900">
                                    <span>Grand Total</span>
                                    <span id="totalText">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card class="space-y-4">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Payment</h2>
                            <p class="mt-1 text-sm text-slate-500">Input metode dan nominal pembayaran.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Payment Method</label>
                            <select name="payment_method"
                                    style="-webkit-appearance:none;-moz-appearance:none;appearance:none;"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 py-3 px-4 text-sm font-medium shadow-sm focus:border-sky-400 focus:bg-white focus:ring-2 focus:ring-sky-100">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                                <option value="card">Card</option>
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <label class="block text-sm font-semibold text-slate-700">Paid Amount</label>
                                <button type="button"
                                        id="exactPaymentButton"
                                        class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700 transition hover:bg-sky-100">
                                    Bayar Pas
                                </button>
                            </div>

                            <input type="number"
                                   name="paid_amount"
                                   id="paidAmount"
                                   value="0"
                                   min="0"
                                   step="0.01"
                                   class="mt-2 w-full rounded-2xl border border-slate-300 bg-white py-3.5 px-4 text-lg font-black text-slate-900 shadow-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-100">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Payment Reference</label>
                            <input type="text"
                                   name="payment_reference"
                                   placeholder="Opsional"
                                   class="mt-2 w-full rounded-2xl border border-slate-300 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-2 focus:ring-sky-100">
                        </div>

                        <div class="flex items-center justify-between rounded-3xl bg-gradient-to-r from-sky-50 to-cyan-50 p-4 ring-1 ring-sky-100">
                            <div>
                                <div class="text-sm font-semibold text-slate-500">Change</div>
                                <div class="text-xs text-slate-400">Kembalian customer</div>
                            </div>
                            <span class="text-xl font-black text-sky-700" id="changeText">Rp 0</span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Notes</label>
                            <textarea name="notes"
                                      rows="3"
                                      placeholder="Catatan transaksi, opsional"
                                      class="mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100"></textarea>
                        </div>

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-500 px-5 py-4 text-sm font-black text-white shadow-sm transition hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Complete Sale
                        </button>
                    </x-ui.card>
                </aside>
            </div>
        </form>
    </div>


    <script>
        const products = {{ Illuminate\Support\Js::from($posProducts) }};

        let cart = [];

        const warehouseId = document.getElementById('warehouseId');
        const barcodeInput = document.getElementById('barcodeInput');
        const productGrid = document.getElementById('productGrid');
        const productSearch = document.getElementById('productSearch');
        const cartItems = document.getElementById('cartItems');
        const subtotalText = document.getElementById('subtotalText');
        const totalText = document.getElementById('totalText');
        const changeText = document.getElementById('changeText');
        const discountAmount = document.getElementById('discountAmount');
        const taxAmount = document.getElementById('taxAmount');
        const paidAmount = document.getElementById('paidAmount');
        const exactPaymentButton = document.getElementById('exactPaymentButton');
        const posForm = document.getElementById('posForm');
        const itemsInputContainer = document.getElementById('itemsInputContainer');

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value || 0);
        }


        function notifyToast(icon, title) {
            if (window.Toast) {
                Toast.fire({ icon, title });
                return;
            }

            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icon,
                    title: title,
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                return;
            }

            console.log(title);
        }

        function notifyAlert(icon, title, text = '') {
            if (window.Swal) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0ea5e9'
                });
                return;
            }

            alert(text || title);
        }

        function selectedWarehouseId() {
            return String(warehouseId.value);
        }

        function isStockTracked(product) {
            return product.track_stock !== false;
        }

        function getProductStock(product) {
            if (!isStockTracked(product)) {
                return 999999999;
            }

            return Number(product.stocks_by_warehouse?.[selectedWarehouseId()] ?? product.stock ?? 0);
        }

        function getCartQuantity(productId) {
            const item = cart.find(item => item.id === productId);

            return item ? Number(item.quantity) : 0;
        }

        function canAddProduct(product, quantityToAdd = 1) {
            if (!isStockTracked(product)) {
                return true;
            }

            const stock = getProductStock(product);
            const currentCartQuantity = getCartQuantity(product.id);

            return currentCartQuantity + quantityToAdd <= stock;
        }

        function renderProducts() {
            const keyword = productSearch.value.toLowerCase();

            const filteredProducts = products.filter(product => {
                return product.name.toLowerCase().includes(keyword)
                    || product.sku.toLowerCase().includes(keyword)
                    || (product.barcode && product.barcode.toLowerCase().includes(keyword));
            });

            if (filteredProducts.length === 0) {
                productGrid.innerHTML = `
                    <div class="col-span-full rounded-3xl border border-dashed border-sky-200 bg-sky-50/60 p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-sky-500 shadow-sm">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </div>
                        <div class="mt-4 font-semibold text-slate-900">Produk tidak ditemukan</div>
                        <p class="mt-1 text-sm text-slate-500">Coba cari dengan nama produk, SKU, atau barcode lain.</p>
                    </div>
                `;
                return;
            }

            productGrid.innerHTML = filteredProducts.map(product => {
                const stock = getProductStock(product);
                const isOutOfStock = isStockTracked(product) && stock <= 0;
                const inCartQty = getCartQuantity(product.id);

                return `
                    <button type="button"
                            onclick="addToCart(${product.id})"
                            class="group overflow-hidden rounded-3xl border border-slate-200/80 bg-white text-left shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md ${isOutOfStock ? 'opacity-60' : ''}">
                        <div class="relative">
                            ${product.image_url
                                ? `<img src="${escapeHtml(product.image_url)}" class="h-36 w-full object-cover transition duration-300 group-hover:scale-105" alt="${escapeHtml(product.name)}">`
                                : `<div class="flex h-36 w-full items-center justify-center bg-gradient-to-br from-sky-50 to-cyan-50 text-xs font-semibold text-sky-300">No Image</div>`
                            }

                            <div class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                                ${escapeHtml(product.category ?? 'Uncategorized')}
                            </div>

                            ${inCartQty > 0
                                ? `<div class="absolute right-3 top-3 rounded-full bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm">Cart: ${inCartQty}</div>`
                                : ''
                            }
                        </div>

                        <div class="p-4">
                            <div class="line-clamp-2 font-bold text-slate-900">${escapeHtml(product.name)}</div>
                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 font-mono">${escapeHtml(product.sku)}</span>
                                ${product.barcode ? `<span class="rounded-full bg-slate-100 px-2 py-0.5 font-mono">${escapeHtml(product.barcode)}</span>` : ''}
                            </div>

                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div>
                                    <div class="text-lg font-black text-slate-900">${formatRupiah(product.price)}</div>
                                    <div class="mt-1 text-xs ${isOutOfStock ? 'font-semibold text-red-600' : 'text-slate-500'}">
                                        Stock: ${isStockTracked(product) ? stock : 'Not tracked'} ${escapeHtml(product.unit ?? '')}
                                    </div>
                                </div>

                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 transition group-hover:bg-sky-500 group-hover:text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14"></path>
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </button>
                `;
            }).join('');
        }

        function addToCart(productId, quantityToAdd = 1) {
            const product = products.find(product => product.id === productId);

            if (!product) {
                notifyAlert('error', 'Produk tidak ditemukan', 'Produk tidak ada dalam daftar POS.');
                return;
            }

            if (!canAddProduct(product, quantityToAdd)) {
                notifyAlert('warning', 'Stok tidak mencukupi', `Stok ${product.name} tidak mencukupi di warehouse ini.`);
                return;
            }

            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity = Number(existingItem.quantity) + quantityToAdd;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: product.price,
                    unit: product.unit,
                    track_stock: isStockTracked(product),
                    quantity: quantityToAdd,
                });
            }

            renderCart();
            renderProducts();
            notifyToast('success', `${product.name} ditambahkan ke cart.`);
        }

        function updateQuantity(productId, quantity) {
            const item = cart.find(item => item.id === productId);

            if (!item) {
                return;
            }

            const product = products.find(product => product.id === productId);
            const newQuantity = Number(quantity || 0);

            if (newQuantity <= 0) {
                removeFromCart(productId);
                return;
            }

            if (isStockTracked(product) && newQuantity > getProductStock(product)) {
                notifyAlert('warning', 'Qty melebihi stok', `Stok tersedia: ${getProductStock(product)} ${product.unit ?? ''}`);
                item.quantity = getProductStock(product);
            } else {
                item.quantity = newQuantity;
            }

            renderCart();
            renderProducts();
        }

        function increaseQuantity(productId) {
            addToCart(productId, 1);
        }

        function decreaseQuantity(productId) {
            const item = cart.find(item => item.id === productId);

            if (!item) {
                return;
            }

            item.quantity = Number(item.quantity) - 1;

            if (item.quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            renderCart();
            renderProducts();
        }

        function removeFromCart(productId) {
            const item = cart.find(item => item.id === productId);
            cart = cart.filter(item => item.id !== productId);
            renderCart();
            renderProducts();

            if (item) {
                notifyToast('info', `${item.name} dihapus dari cart.`);
            }
        }

        function calculateSubtotal() {
            return cart.reduce((total, item) => total + (Number(item.price) * Number(item.quantity)), 0);
        }

        function calculateTotal() {
            const subtotal = calculateSubtotal();
            const discount = Number(discountAmount.value || 0);
            const tax = Number(taxAmount.value || 0);

            return Math.max(subtotal - discount + tax, 0);
        }

        function renderCart() {
            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="rounded-3xl border border-dashed border-sky-200 bg-sky-50/70 p-6 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-sky-500 shadow-sm">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="8" cy="21" r="1"></circle>
                                <circle cx="19" cy="21" r="1"></circle>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57L22 7H5.12"></path>
                            </svg>
                        </div>
                        <div class="mt-3 font-semibold text-slate-700">Cart masih kosong</div>
                        <p class="mt-1 text-xs text-slate-500">Scan barcode atau pilih produk untuk mulai transaksi.</p>
                    </div>
                `;
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="rounded-3xl border border-slate-200/80 bg-white p-4 shadow-sm">
                        <div class="flex justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate font-bold text-slate-900">${escapeHtml(item.name)}</div>
                                <div class="mt-1 text-xs text-slate-500">${escapeHtml(item.sku)} · ${formatRupiah(item.price)} / ${escapeHtml(item.unit ?? 'unit')}</div>
                            </div>

                            <button type="button"
                                    onclick="removeFromCart(${item.id})"
                                    class="rounded-xl px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50">
                                Remove
                            </button>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <div class="flex items-center rounded-2xl border border-slate-200 bg-slate-50 p-1">
                                <button type="button"
                                        onclick="decreaseQuantity(${item.id})"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sm font-bold text-slate-700 shadow-sm hover:bg-sky-50 hover:text-sky-700">
                                    -
                                </button>

                                <input type="number"
                                       value="${item.quantity}"
                                       min="0.01"
                                       step="0.01"
                                       onchange="updateQuantity(${item.id}, this.value)"
                                       class="h-9 w-20 border-0 bg-transparent text-center text-sm font-bold text-slate-900 focus:ring-0">

                                <button type="button"
                                        onclick="increaseQuantity(${item.id})"
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sm font-bold text-slate-700 shadow-sm hover:bg-sky-50 hover:text-sky-700">
                                    +
                                </button>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-slate-500">Subtotal</div>
                                <div class="font-black text-slate-900">${formatRupiah(item.price * item.quantity)}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            const subtotal = calculateSubtotal();
            const total = calculateTotal();
            const paid = Number(paidAmount.value || 0);
            const change = Math.max(paid - total, 0);

            subtotalText.textContent = formatRupiah(subtotal);
            totalText.textContent = formatRupiah(total);
            changeText.textContent = formatRupiah(change);
        }

        function syncCartToInputs() {
            itemsInputContainer.innerHTML = '';

            cart.forEach((item, index) => {
                itemsInputContainer.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                `);
            });
        }

        function scanBarcode(value) {
            const keyword = value.trim().toLowerCase();

            if (!keyword) {
                return;
            }

            const product = products.find(product => {
                return String(product.barcode ?? '').toLowerCase() === keyword
                    || String(product.sku ?? '').toLowerCase() === keyword;
            });

            if (!product) {
                notifyAlert('error', 'Produk tidak ditemukan', 'Barcode atau SKU tidak cocok dengan data produk.');
                barcodeInput.select();
                return;
            }

            addToCart(product.id);
            barcodeInput.value = '';
            barcodeInput.focus();
        }

        function validateCartBeforeSubmit() {
            if (cart.length === 0) {
                notifyToast('warning', 'Cart masih kosong.');
                return false;
            }

            for (const item of cart) {
                const product = products.find(product => product.id === item.id);

                if (!product) {
                    notifyAlert('error', 'Produk tidak valid', `Produk ${item.name} tidak valid.`);
                    return false;
                }

                if (isStockTracked(product) && Number(item.quantity) > getProductStock(product)) {
                    notifyAlert('warning', 'Qty melebihi stok warehouse', `Qty ${product.name} melebihi stok warehouse. Stok tersedia: ${getProductStock(product)} ${product.unit ?? ''}`);
                    return false;
                }
            }

            const total = calculateTotal();
            const paid = Number(paidAmount.value || 0);

            if (paid < total) {
                notifyAlert('warning', 'Pembayaran kurang', 'Nominal bayar kurang dari total transaksi.');
                paidAmount.focus();
                return false;
            }

            return true;
        }

        warehouseId.addEventListener('change', function () {
            cart = [];
            renderProducts();
            renderCart();
            barcodeInput.focus();
            notifyToast('info', 'Warehouse diganti, cart dikosongkan.');
        });

        barcodeInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                scanBarcode(barcodeInput.value);
            }
        });

        productSearch.addEventListener('input', renderProducts);
        discountAmount.addEventListener('input', renderCart);
        taxAmount.addEventListener('input', renderCart);
        paidAmount.addEventListener('input', renderCart);

        exactPaymentButton.addEventListener('click', function () {
            paidAmount.value = calculateTotal();
            renderCart();
            paidAmount.focus();
            paidAmount.select();
            notifyToast('success', 'Nominal bayar diisi pas.');
        });

        posForm.addEventListener('submit', function (event) {
            if (!validateCartBeforeSubmit()) {
                event.preventDefault();
                return;
            }

            syncCartToInputs();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'F2') {
                event.preventDefault();
                productSearch.focus();
                productSearch.select();
            }

            if (event.key === 'F4') {
                event.preventDefault();
                barcodeInput.focus();
                barcodeInput.select();
            }

            if (event.key === 'F9') {
                event.preventDefault();
                paidAmount.focus();
                paidAmount.select();
            }

            if (event.ctrlKey && event.key === 'Enter') {
                event.preventDefault();

                if (validateCartBeforeSubmit()) {
                    syncCartToInputs();
                    posForm.submit();
                }
            }
        });

        const posShortcutHelpButton = document.getElementById('posShortcutHelpButton');

        if (posShortcutHelpButton) {
            posShortcutHelpButton.addEventListener('click', function () {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Shortcut POS',
                        html: `
                            <div class="space-y-3 text-left text-sm">
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-3 py-2"><span>F2</span><strong>Search produk</strong></div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-3 py-2"><span>F4</span><strong>Scan barcode</strong></div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-3 py-2"><span>F9</span><strong>Input nominal bayar</strong></div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-3 py-2"><span>Ctrl + Enter</span><strong>Complete sale</strong></div>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0ea5e9'
                    });
                    return;
                }

                alert('F2: Search produk, F4: Scan barcode, F9: Paid amount, Ctrl+Enter: Complete sale');
            });
        }

        renderProducts();
        renderCart();

        barcodeInput.focus();
    </script>
</x-layouts.app>