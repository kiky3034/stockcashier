<x-layouts.app :title="__('Backups')">
    <div class="p-6">
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Backups</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Download backup database StockCashier.
                </p>
            </div>

            <x-flash-message />

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900">Database Backup</h2>

                <p class="mt-2 text-sm text-gray-600">
                    File backup berisi struktur tabel dan data database dalam format SQL.
                    Simpan file ini di tempat aman.
                </p>

                <div class="mt-5">
                    <a href="{{ route('admin.backups.database') }}"
                       class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                       onclick="return confirm('Download backup database sekarang?')">
                        Download Database Backup
                    </a>
                </div>
            </div>

            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800">
                Backup ini sederhana dan cocok untuk tahap awal. Untuk production besar,
                sebaiknya tetap gunakan backup otomatis dari server/hosting.
            </div>
        </div>
    </div>
</x-layouts.app>