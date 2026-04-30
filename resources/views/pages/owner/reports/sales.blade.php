<x-layouts.app :title="__('Sales Report')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Laporan penjualan berdasarkan periode.
                </p>
            </div>

            <a href="{{ route('owner.dashboard') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Back to Dashboard
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('owner.reports.sales') }}" class="grid gap-3 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">From</label>
                    <input type="date"
                           name="from"
                           value="{{ $from->format('Y-m-d') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">To</label>
                    <input type="date"
                           name="to"
                           value="{{ $to->format('Y-m-d') }}"
                           class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Filter
                    </button>

                    <a href="{{ route('owner.reports.sales') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Gross Sales</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    Rp {{ number_format($grossSales, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Refund</div>
                <div class="mt-2 text-xl font-bold text-red-700">
                    Rp {{ number_format($refundTotal, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Net Sales</div>
                <div class="mt-2 text-xl font-bold text-green-700">
                    Rp {{ number_format($netSales, 0, ',', '.') }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="text-sm text-gray-500">Transactions</div>
                <div class="mt-2 text-xl font-bold text-gray-900">
                    {{ number_format($transactionCount, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <h2 class="font-semibold text-gray-900">Sales List</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Invoice</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Cashier</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Warehouse</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($sales as $sale)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $sale->invoice_number }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $sale->sold_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $sale->cashier->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $sale->warehouse->name }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ str_replace('_', ' ', ucfirst($sale->status)) }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada penjualan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>