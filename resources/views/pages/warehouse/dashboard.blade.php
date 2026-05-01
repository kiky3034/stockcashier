<x-layouts.app :title="__('Warehouse Dashboard')">
    <div class="p-6 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Warehouse Dashboard</h1>
                <p class="mt-2 text-gray-600">
                    Kelola produk, stok, stock adjustment, dan barang masuk.
                </p>
            </div>

            <button type="button"
                    id="warehouseGuideButton"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Panduan Gudang
            </button>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.products.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Products"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50 transition-all hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Products</h2>
                        <p class="mt-1 text-sm text-gray-600">Kelola produk dan harga.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.stocks.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Stocks"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50 transition-all hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Stocks</h2>
                        <p class="mt-1 text-sm text-gray-600">Pantau stok per warehouse.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.stock-movements.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Stock Movements"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50 transition-all hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-purple-100 p-3">
                        <svg class="h-6 w-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Stock Movements</h2>
                        <p class="mt-1 text-sm text-gray-600">Lihat riwayat keluar-masuk stok.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.purchases.create') }}"
               data-dashboard-link
               data-toast-title="Membuka New Purchase"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50 transition-all hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-orange-100 p-3">
                        <svg class="h-6 w-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">New Purchase</h2>
                        <p class="mt-1 text-sm text-gray-600">Input barang masuk supplier.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const guideButton = document.getElementById('warehouseGuideButton');

            if (guideButton && window.Swal) {
                guideButton.addEventListener('click', function () {
                    Swal.fire({
                        icon: 'info',
                        title: 'Panduan Warehouse Staff',
                        html: `
                            <div class="text-left text-sm leading-6">
                                <ol class="list-decimal space-y-2 pl-5">
                                    <li><strong>Products</strong>: cek data produk, SKU, barcode, harga, dan status aktif.</li>
                                    <li><strong>Stocks</strong>: pantau stok per warehouse dan item yang menipis.</li>
                                    <li><strong>Stock Movements</strong>: cek riwayat stok masuk dan keluar.</li>
                                    <li><strong>New Purchase</strong>: input barang masuk dari supplier.</li>
                                </ol>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#111827'
                    });
                });
            }

            document.querySelectorAll('[data-dashboard-link]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.Toast) {
                        Toast.fire({
                            icon: 'info',
                            title: link.dataset.toastTitle || 'Membuka halaman'
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>
