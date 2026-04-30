<x-layouts.app :title="__('Cashier Dashboard')">
    <div class="p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cashier Dashboard</h1>
            <p class="mt-2 text-gray-600">Mulai transaksi penjualan dari sini.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('cashier.pos.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">POS</h2>
                <p class="mt-1 text-sm text-gray-600">Buat transaksi penjualan baru.</p>
            </a>

            <a href="{{ route('cashier.sales.index') }}"
               class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:bg-gray-50">
                <h2 class="font-semibold text-gray-900">Sales History</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat riwayat transaksi.</p>
            </a>
        </div>
    </div>
</x-layouts.app>