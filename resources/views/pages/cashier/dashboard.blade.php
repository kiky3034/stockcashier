<x-layouts.app :title="__('Cashier Dashboard')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Cashier Dashboard"
            description="Mulai transaksi penjualan, scan barcode, dan cetak ulang struk dari satu tempat."
        >
            <x-slot:actions>
                <button type="button"
                        id="cashierShortcutButton"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-sky-200 bg-white px-4 py-2.5 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-50 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18h6" />
                        <path d="M10 22h4" />
                        <path d="M2 12a10 10 0 1 1 20 0c0 3.2-1.5 5.5-4.1 7.3-.7.5-1.1 1.3-1.1 2.1H7.2c0-.8-.4-1.6-1.1-2.1C3.5 17.5 2 15.2 2 12Z" />
                    </svg>
                    Tips Shortcut
                </button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-5 lg:grid-cols-3">
            <a href="{{ route('cashier.pos.index') }}"
               class="group relative overflow-hidden rounded-3xl border border-sky-100 bg-gradient-to-br from-sky-500 via-sky-500 to-cyan-400 p-6 text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-sky-100">
                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/20"></div>
                <div class="absolute -bottom-12 right-10 h-28 w-28 rounded-full bg-white/10"></div>

                <div class="relative">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/30">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v6H4z" />
                            <path d="M6 14h12" />
                            <path d="M7 18h2" />
                            <path d="M11 18h2" />
                            <path d="M15 18h2" />
                            <path d="M5 10v10h14V10" />
                        </svg>
                    </div>

                    <h2 class="mt-5 text-xl font-bold">POS</h2>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-sky-50">
                        Buat transaksi baru, scan barcode/SKU, tambah item ke cart, dan proses pembayaran.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-sky-700 shadow-sm transition group-hover:bg-sky-50">
                        Buka POS
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('cashier.sales.index') }}"
               class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-sky-100 lg:col-span-2">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 11h6" />
                                <path d="M9 15h6" />
                                <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z" />
                                <path d="M14 3v5h5" />
                            </svg>
                        </div>

                        <h2 class="mt-5 text-xl font-bold text-slate-900">Sales History</h2>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">
                            Lihat riwayat transaksi, detail invoice, cetak ulang struk, dan kelola refund/void sesuai izin role kasir.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition group-hover:border-sky-200 group-hover:bg-sky-50 group-hover:text-sky-700">
                        Lihat Riwayat
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <x-ui.card class="lg:col-span-2">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Alur Cepat Kasir</h2>
                        <p class="mt-1 text-sm text-slate-500">Checklist singkat sebelum memproses transaksi.</p>
                    </div>

                    <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                        Recommended
                    </span>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                1
                            </div>
                            <div class="font-semibold text-slate-900">Pilih warehouse</div>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Pastikan warehouse sesuai lokasi transaksi supaya stok yang dipakai benar.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                2
                            </div>
                            <div class="font-semibold text-slate-900">Scan barcode/SKU</div>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Gunakan scanner atau ketik SKU, lalu tekan Enter untuk menambah produk ke cart.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                3
                            </div>
                            <div class="font-semibold text-slate-900">Cek pembayaran</div>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Gunakan tombol Bayar Pas untuk transaksi tunai tanpa kembalian.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                                4
                            </div>
                            <div class="font-semibold text-slate-900">Cetak struk</div>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Cetak receipt dari halaman invoice atau Sales History bila dibutuhkan ulang.
                        </p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-base font-bold text-slate-900">Shortcut POS</h2>
                <p class="mt-1 text-sm text-slate-500">Gunakan keyboard agar proses kasir lebih cepat.</p>

                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-3 text-sm">
                        <span class="font-medium text-slate-700">Search produk</span>
                        <kbd class="rounded-lg bg-white px-2 py-1 text-xs font-bold text-sky-700 shadow-sm">F2</kbd>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-3 text-sm">
                        <span class="font-medium text-slate-700">Scan barcode</span>
                        <kbd class="rounded-lg bg-white px-2 py-1 text-xs font-bold text-sky-700 shadow-sm">F4</kbd>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-3 text-sm">
                        <span class="font-medium text-slate-700">Input bayar</span>
                        <kbd class="rounded-lg bg-white px-2 py-1 text-xs font-bold text-sky-700 shadow-sm">F9</kbd>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-sky-50 px-4 py-3 text-sm">
                        <span class="font-medium text-slate-700">Complete sale</span>
                        <kbd class="rounded-lg bg-white px-2 py-1 text-xs font-bold text-sky-700 shadow-sm">Ctrl + Enter</kbd>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const shortcutButton = document.getElementById('cashierShortcutButton');

            if (!shortcutButton) {
                return;
            }

            shortcutButton.addEventListener('click', function () {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Shortcut POS',
                        html: `
                            <div class="space-y-3 text-left text-sm text-slate-700">
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-4 py-3">
                                    <span>Fokus ke search produk</span><strong>F2</strong>
                                </div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-4 py-3">
                                    <span>Fokus ke scan barcode / SKU</span><strong>F4</strong>
                                </div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-4 py-3">
                                    <span>Fokus ke nominal bayar</span><strong>F9</strong>
                                </div>
                                <div class="flex justify-between gap-4 rounded-xl bg-sky-50 px-4 py-3">
                                    <span>Complete sale</span><strong>Ctrl + Enter</strong>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0ea5e9'
                    });

                    return;
                }

                if (window.Toast) {
                    Toast.fire({
                        icon: 'info',
                        title: 'Shortcut POS: F2 search, F4 barcode, F9 bayar, Ctrl+Enter checkout.'
                    });
                }
            });
        });
    </script>
</x-layouts.app>
