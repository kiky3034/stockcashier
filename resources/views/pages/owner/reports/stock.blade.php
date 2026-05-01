<x-layouts.app :title="__('Stock Report')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Stock Report"
            description="Laporan stok produk per warehouse, nilai inventory, dan status low stock."
        >
            <x-slot:actions>
                <button type="button"
                        data-copy-report-summary
                        data-copy-title="Ringkasan stok disalin"
                        data-summary="Stock Report | Total Quantity: {{ number_format($totalQuantity, 2, ',', '.') }} | Inventory Value: Rp {{ number_format($totalStockValue, 0, ',', '.') }} | Low Stock Items: {{ number_format($lowStockCount, 0, ',', '.') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                    Copy Summary
                </button>
                <a href="{{ route('owner.reports.stock.export', request()->query()) }}" data-export-confirm data-export-title="Export stock report?" data-export-text="Stock report sesuai filter saat ini akan didownload sebagai CSV." class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">Export CSV</a>
                <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">Dashboard</a>
            </x-slot:actions>
        </x-page-header>

        <x-ui.card>
            <form method="GET" action="{{ route('owner.reports.stock') }}" class="grid gap-4 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700">Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk, SKU, barcode..." class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Warehouse</label>
                    <select name="warehouse_id" class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Warehouses</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @selected($warehouseId == $warehouse->id)>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Status</label>
                    <select name="status" class="py-2 mt-2 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Status</option>
                        <option value="low" @selected($status === 'low')>Low Stock</option>
                        <option value="safe" @selected($status === 'safe')>Safe</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">Filter</button>
                    <a href="{{ route('owner.reports.stock') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-sky-50">Reset</a>
                </div>
            </form>
        </x-ui.card>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card label="Total Quantity" value="{{ number_format($totalQuantity, 2, ',', '.') }}" tone="sky" />
            <x-ui.stat-card label="Inventory Value" value="Rp {{ number_format($totalStockValue, 0, ',', '.') }}" tone="green" />
            <x-ui.stat-card label="Low Stock Items" value="{{ number_format($lowStockCount, 0, ',', '.') }}" tone="red" />
        </div>

        <x-ui.card padding="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-sky-50/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Category</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Warehouse</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Quantity</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Alert</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Value</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($stocks as $stock)
                            <tr class="hover:bg-sky-50/40">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $stock->product->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">SKU: {{ $stock->product->sku }}</div>
                                    <div class="mt-1 font-mono text-xs text-slate-500">Barcode: {{ $stock->product->barcode ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $stock->product->category?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $stock->warehouse->name }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ number_format($stock->quantity, 2, ',', '.') }} {{ $stock->product->unit?->abbreviation }}</td>
                                <td class="px-4 py-3 text-right text-slate-600">{{ number_format($stock->product->stock_alert_level, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($stock->quantity * $stock->product->cost_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if ($stock->quantity <= $stock->product->stock_alert_level)
                                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-100">Low Stock</span>
                                    @else
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Safe</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-slate-500">Tidak ada data stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 p-4">{{ $stocks->links() }}</div>
        </x-ui.card>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lowStockCount = Number(@json($lowStockCount));

        if (lowStockCount > 0 && window.Toast) {
            Toast.fire({
                icon: 'warning',
                title: `${lowStockCount} item low stock`
            });
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reportToast = window.Toast || {
            fire: function (options) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        ...options
                    });
                }
            }
        };

        document.querySelectorAll('[data-export-confirm]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                if (!window.Swal) {
                    window.location.href = link.href;
                    return;
                }

                Swal.fire({
                    title: link.dataset.exportTitle || 'Export CSV?',
                    text: link.dataset.exportText || 'Data sesuai filter saat ini akan didownload.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, export',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#0ea5e9',
                    cancelButtonColor: '#64748b'
                }).then((result) => {
                    if (result.isConfirmed) {
                        reportToast.fire({
                            icon: 'info',
                            title: 'Menyiapkan file CSV...'
                        });

                        window.location.href = link.href;
                    }
                });
            });
        });

        document.querySelectorAll('[data-copy-report-summary]').forEach(function (button) {
            button.addEventListener('click', async function () {
                const summary = button.dataset.summary || '';
                const title = button.dataset.copyTitle || 'Ringkasan disalin';

                try {
                    await navigator.clipboard.writeText(summary);

                    reportToast.fire({
                        icon: 'success',
                        title: title
                    });
                } catch (error) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menyalin',
                            text: 'Browser tidak mengizinkan akses clipboard.',
                            confirmButtonColor: '#0ea5e9'
                        });
                    }
                }
            });
        });
    });
</script>

</x-layouts.app>
