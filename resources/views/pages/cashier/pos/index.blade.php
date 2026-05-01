<x-layouts.app :title="__('POS')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">POS Cashier</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Buat transaksi penjualan baru.
                </p>
            </div>

            <a href="{{ route('cashier.sales.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Sales History
            </a>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('cashier.pos.store') }}" id="posForm">
            @csrf

            <div id="itemsInputContainer"></div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="grid gap-3 md:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Warehouse</label>
                                <select name="warehouse_id"
                                        id="warehouseId"
                                        class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">
                                            {{ $warehouse->name }} — {{ $warehouse->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Scan Barcode / SKU
                                </label>
                                <input type="text"
                                       id="barcodeInput"
                                       placeholder="Scan barcode lalu Enter..."
                                       autocomplete="off"
                                       class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Search Product
                                </label>
                                <input type="text"
                                       id="productSearch"
                                       placeholder="Cari produk, SKU, barcode..."
                                       autocomplete="off"
                                       class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            </div>
                        </div>

                        <div class="mt-3 rounded-lg bg-gray-50 p-3 text-xs text-gray-600">
                            Shortcut:
                            <span class="font-semibold">F2</span> search produk,
                            <span class="font-semibold">F4</span> scan barcode,
                            <span class="font-semibold">F9</span> input bayar,
                            <span class="font-semibold">Ctrl + Enter</span> complete sale.
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h2 class="mb-4 font-semibold text-gray-900">Products</h2>

                        <div id="productGrid" class="grid gap-3 md:grid-cols-2 xl:grid-cols-3"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h2 class="font-semibold text-gray-900">Cart</h2>

                        <div id="cartItems" class="mt-4 space-y-3"></div>

                        <div class="mt-4 border-t border-gray-200 pt-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900" id="subtotalText">Rp 0</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount</label>
                                <input type="number"
                                       name="discount_amount"
                                       id="discountAmount"
                                       value="0"
                                       min="0"
                                       step="0.01"
                                       class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tax</label>
                                <input type="number"
                                       name="tax_amount"
                                       id="taxAmount"
                                       value="0"
                                       min="0"
                                       step="0.01"
                                       class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            </div>

                            <div class="flex justify-between border-t border-gray-200 pt-3 text-base">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-gray-900" id="totalText">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm space-y-4">
                        <h2 class="font-semibold text-gray-900">Payment</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="payment_method"
                                    class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                                <option value="card">Card</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Paid Amount</label>
                            <input type="number"
                                   name="paid_amount"
                                   id="paidAmount"
                                   value="0"
                                   min="0"
                                   step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                            <button type="button"
                                    id="exactPaymentButton"
                                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Bayar Pas
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                            <input type="text"
                                   name="payment_reference"
                                   placeholder="Opsional"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                        </div>

                        <div class="flex justify-between rounded-lg bg-gray-50 p-3 text-sm">
                            <span class="text-gray-600">Change</span>
                            <span class="font-bold text-gray-900" id="changeText">Rp 0</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes"
                                      rows="3"
                                      class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-700">
                            Complete Sale
                        </button>
                    </div>
                </div>
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

            productGrid.innerHTML = filteredProducts.map(product => {
                const stock = getProductStock(product);
                const isOutOfStock = isStockTracked(product) && stock <= 0;

                return `
                    <button type="button"
                            onclick="addToCart(${product.id})"
                            class="rounded-xl border border-gray-200 p-4 text-left hover:bg-gray-50 ${isOutOfStock ? 'opacity-60' : ''}">
                        ${product.image_url
                            ? `<img src="${escapeHtml(product.image_url)}" class="mb-3 h-28 w-full rounded-lg object-cover" alt="${escapeHtml(product.name)}">`
                            : `<div class="mb-3 flex h-28 w-full items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-400">No Image</div>`
                        }

                        <div class="font-semibold text-gray-900">${escapeHtml(product.name)}</div>
                        <div class="mt-1 text-xs text-gray-500">SKU: ${escapeHtml(product.sku)}</div>
                        <div class="mt-1 text-xs text-gray-500">${escapeHtml(product.category ?? '-')}</div>
                        <div class="mt-2 font-bold text-gray-900">${formatRupiah(product.price)}</div>

                        <div class="mt-1 text-xs ${isOutOfStock ? 'font-semibold text-red-600' : 'text-gray-500'}">
                            Stock: ${isStockTracked(product) ? stock : 'Not tracked'} ${escapeHtml(product.unit ?? '')}
                        </div>
                    </button>
                `;
            }).join('');
        }

        function addToCart(productId, quantityToAdd = 1) {
            const product = products.find(product => product.id === productId);

            if (!product) {
                alert('Produk tidak ditemukan.');
                return;
            }

            if (!canAddProduct(product, quantityToAdd)) {
                alert(`Stok ${product.name} tidak mencukupi di warehouse ini.`);
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
                alert(`Qty melebihi stok. Stok tersedia: ${getProductStock(product)} ${product.unit ?? ''}`);
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
            cart = cart.filter(item => item.id !== productId);
            renderCart();
            renderProducts();
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
                    <div class="rounded-lg border border-dashed border-gray-300 p-4 text-center text-sm text-gray-500">
                        Cart masih kosong.
                    </div>
                `;
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="flex justify-between gap-3">
                            <div>
                                <div class="font-medium text-gray-900">${escapeHtml(item.name)}</div>
                                <div class="mt-1 text-xs text-gray-500">${escapeHtml(item.sku)}</div>
                                <div class="mt-1 text-xs text-gray-500">${formatRupiah(item.price)} / ${escapeHtml(item.unit ?? 'unit')}</div>
                            </div>

                            <button type="button"
                                    onclick="removeFromCart(${item.id})"
                                    class="text-xs font-semibold text-red-600">
                                Remove
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="decreaseQuantity(${item.id})"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50">
                                    -
                                </button>

                                <input type="number"
                                       value="${item.quantity}"
                                       min="0.01"
                                       step="0.01"
                                       onchange="updateQuantity(${item.id}, this.value)"
                                       class="w-24 rounded-lg border-gray-300 text-center text-sm focus:border-gray-900 focus:ring-gray-900">

                                <button type="button"
                                        onclick="increaseQuantity(${item.id})"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50">
                                    +
                                </button>
                            </div>

                            <div class="font-semibold text-gray-900">
                                ${formatRupiah(item.price * item.quantity)}
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
                alert('Produk dengan barcode/SKU tersebut tidak ditemukan.');
                barcodeInput.select();
                return;
            }

            addToCart(product.id);
            barcodeInput.value = '';
            barcodeInput.focus();
        }

        function validateCartBeforeSubmit() {
            if (cart.length === 0) {
                alert('Cart masih kosong.');
                return false;
            }

            for (const item of cart) {
                const product = products.find(product => product.id === item.id);

                if (!product) {
                    alert(`Produk ${item.name} tidak valid.`);
                    return false;
                }

                if (isStockTracked(product) && Number(item.quantity) > getProductStock(product)) {
                    alert(`Qty ${product.name} melebihi stok warehouse. Stok tersedia: ${getProductStock(product)} ${product.unit ?? ''}`);
                    return false;
                }
            }

            const total = calculateTotal();
            const paid = Number(paidAmount.value || 0);

            if (paid < total) {
                alert('Nominal bayar kurang dari total transaksi.');
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

        renderProducts();
        renderCart();

        barcodeInput.focus();
    </script>
</x-layouts.app>
