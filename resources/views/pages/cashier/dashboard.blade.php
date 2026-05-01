<x-layouts.app :title="__('Cashier Dashboard')">
    <div class="p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cashier Dashboard</h1>
                <p class="mt-2 text-gray-600">Mulai transaksi penjualan dari sini.</p>
            </div>

            <button type="button"
                    id="cashierShortcutButton"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Tips Shortcut
            </button>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('cashier.pos.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">POS</h2>
                <p class="mt-1 text-sm text-gray-600">Buat transaksi penjualan baru.</p>
                <p class="mt-3 text-xs text-gray-500">Shortcut di POS: F2 search, F4 barcode, F9 bayar.</p>
            </a>

            <a href="{{ route('cashier.sales.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Sales History</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat riwayat transaksi.</p>
                <p class="mt-3 text-xs text-gray-500">Cetak ulang struk dan cek detail invoice.</p>
            </a>
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
                            <div class="text-left text-sm">
                                <div><strong>F2</strong> — Fokus ke search produk</div>
                                <div><strong>F4</strong> — Fokus ke scan barcode / SKU</div>
                                <div><strong>F9</strong> — Fokus ke nominal bayar</div>
                                <div><strong>Ctrl + Enter</strong> — Complete sale</div>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#111827'
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
