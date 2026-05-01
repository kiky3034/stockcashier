<x-layouts.app :title="__('Warehouse Dashboard')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Warehouse Dashboard"
            description="Kelola produk, pantau stok, cek riwayat movement, dan input barang masuk supplier."
        >
            <x-slot:actions>
                <button type="button"
                        id="warehouseGuideButton"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-200 bg-white px-4 py-2.5 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-50 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 17h.01" />
                        <path d="M12 13v-2" />
                        <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Panduan Gudang
                </button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-hidden rounded-3xl border border-sky-100 bg-gradient-to-br from-sky-500 via-cyan-500 to-blue-600 p-6 text-white shadow-sm sm:p-7">
            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-center">
                <div>
                    <div class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-sky-50 ring-1 ring-white/20">
                        Warehouse Workspace
                    </div>

                    <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">
                        Pantau dan gerakkan stok dengan lebih cepat.
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-sky-50/90">
                        Gunakan shortcut di bawah untuk mengelola data produk, mengecek stok, melihat histori movement,
                        dan mencatat barang masuk dari supplier.
                    </p>
                </div>

                <div class="rounded-2xl bg-white/15 p-4 ring-1 ring-white/20 backdrop-blur">
                    <div class="text-sm font-semibold text-white">Alur kerja gudang</div>
                    <div class="mt-3 space-y-2 text-sm text-sky-50/90">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-xs font-bold text-sky-600">1</span>
                            Cek produk dan barcode
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-xs font-bold text-sky-600">2</span>
                            Pantau stok per warehouse
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-xs font-bold text-sky-600">3</span>
                            Input purchase saat barang masuk
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('admin.products.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Products"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-slate-500">Master Data</div>
                        <h2 class="mt-2 text-lg font-bold text-slate-900">Products</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Kelola produk, SKU, barcode, harga, dan status aktif.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100 transition group-hover:bg-sky-500 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>

                <div class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-sky-700">
                    Buka Products
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.stocks.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Stocks"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-slate-500">Inventory</div>
                        <h2 class="mt-2 text-lg font-bold text-slate-900">Stocks</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Pantau stok per warehouse dan item yang mulai menipis.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600 ring-1 ring-emerald-100 transition group-hover:bg-emerald-500 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2Zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2Z" />
                        </svg>
                    </div>
                </div>

                <div class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-sky-700">
                    Buka Stocks
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.stock-movements.index') }}"
               data-dashboard-link
               data-toast-title="Membuka Stock Movements"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-slate-500">History</div>
                        <h2 class="mt-2 text-lg font-bold text-slate-900">Stock Movements</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Lihat riwayat keluar-masuk stok dan perubahan quantity.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-violet-50 p-3 text-violet-600 ring-1 ring-violet-100 transition group-hover:bg-violet-500 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0-4-4m4 4-4 4m0 6H4m0 0 4 4m-4-4 4-4" />
                        </svg>
                    </div>
                </div>

                <div class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-sky-700">
                    Buka Movements
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.purchases.create') }}"
               data-dashboard-link
               data-toast-title="Membuka New Purchase"
               class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-slate-500">Barang Masuk</div>
                        <h2 class="mt-2 text-lg font-bold text-slate-900">New Purchase</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Input barang masuk dari supplier ke warehouse tujuan.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-amber-50 p-3 text-amber-600 ring-1 ring-amber-100 transition group-hover:bg-amber-500 group-hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
                        </svg>
                    </div>
                </div>

                <div class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-sky-700">
                    Buat Purchase
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <x-ui.card class="lg:col-span-2">
                <div class="flex items-start gap-4">
                    <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7h18" />
                            <path d="M5 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7" />
                            <path d="M9 7V5a3 3 0 0 1 6 0v2" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="font-semibold text-slate-900">Checklist operasional harian</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Gunakan checklist ini untuk menjaga data stok tetap akurat sebelum dan sesudah input barang masuk.
                        </p>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                                <div class="font-semibold text-slate-900">Sebelum purchase</div>
                                <p class="mt-1">Cek produk, supplier, warehouse tujuan, dan harga modal terakhir.</p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                                <div class="font-semibold text-slate-900">Sesudah purchase</div>
                                <p class="mt-1">Pastikan stok bertambah dan stock movement tercatat sebagai purchase.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="font-semibold text-slate-900">Shortcut Cepat</h2>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('admin.stock-adjustments.create') }}"
                       class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                        Stock Adjustment
                        <span class="text-sky-600">→</span>
                    </a>

                    <a href="{{ route('admin.purchases.index') }}"
                       class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                        Purchase History
                        <span class="text-sky-600">→</span>
                    </a>

                    <a href="{{ route('admin.stock-movements.index') }}"
                       class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                        Movement History
                        <span class="text-sky-600">→</span>
                    </a>
                </div>
            </x-ui.card>
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
                            <div class="text-left text-sm leading-6 text-slate-600">
                                <ol class="list-decimal space-y-2 pl-5">
                                    <li><strong>Products</strong>: cek data produk, SKU, barcode, harga, dan status aktif.</li>
                                    <li><strong>Stocks</strong>: pantau stok per warehouse dan item yang menipis.</li>
                                    <li><strong>Stock Movements</strong>: cek riwayat stok masuk dan keluar.</li>
                                    <li><strong>New Purchase</strong>: input barang masuk dari supplier.</li>
                                    <li><strong>Stock Adjustment</strong>: gunakan hanya untuk koreksi stok manual.</li>
                                </ol>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0ea5e9'
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
