<x-layouts.app :title="$purchase->purchase_number">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Purchase {{ $purchase->purchase_number }}"
            description="{{ $purchase->purchased_at?->format('d M Y H:i') }}"
        >
            <x-slot:actions>
                <button type="button"
                        data-copy-text="{{ $purchase->purchase_number }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    Copy Number
                </button>

                <x-ui.link-button href="{{ route('admin.purchases.create') }}">
                    New Purchase
                </x-ui.link-button>

                <x-ui.link-button href="{{ route('admin.purchases.index') }}" variant="secondary">
                    Back
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-4">
            <x-ui.stat-card label="Supplier" value="{{ $purchase->supplier->name }}" description="Supplier source" tone="sky" />
            <x-ui.stat-card label="Warehouse" value="{{ $purchase->warehouse->name }}" description="Destination stock" tone="slate" />
            <x-ui.stat-card label="Created By" value="{{ $purchase->user->name }}" description="Input user" tone="slate" />
            <x-ui.stat-card label="Status" value="{{ ucfirst($purchase->status) }}" description="Purchase status" tone="green" />
        </div>

        <x-ui.card padding="p-0">
            <div class="flex items-center justify-between border-b border-slate-100 p-4 sm:p-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Purchase Items</h2>
                    <p class="mt-1 text-sm text-slate-500">Daftar produk yang masuk dari purchase ini.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Product</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Qty</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Unit Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($purchase->items as $item)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $item->product_name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">SKU: {{ $item->sku }}</div>
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600">
                                    {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit_name }}
                                </td>
                                <td class="px-4 py-3 text-right text-slate-600">
                                    Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            @if ($purchase->notes)
                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Notes</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $purchase->notes }}</p>
                </x-ui.card>
            @else
                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Notes</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Tidak ada notes untuk purchase ini.</p>
                </x-ui.card>
            @endif

            <x-ui.card>
                <h2 class="font-bold text-slate-900">Summary</h2>

                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($purchase->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Discount</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($purchase->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tax</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($purchase->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-slate-900">Total</span>
                            <span class="text-xl font-black text-sky-700">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon: icon, title: title });
                    return;
                }
                if (window.Swal) {
                    Swal.fire({ icon: icon, title: title, timer: 1800, showConfirmButton: false });
                }
            }

            document.querySelectorAll('[data-copy-text]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const text = button.dataset.copyText || '';
                    try {
                        await navigator.clipboard.writeText(text);
                        showToast('success', 'Purchase number disalin.');
                    } catch (error) {
                        showToast('error', 'Gagal menyalin purchase number.');
                    }
                });
            });
        });
    </script>
</x-layouts.app>
