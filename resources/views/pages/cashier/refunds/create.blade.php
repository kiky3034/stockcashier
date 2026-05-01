<x-layouts.app :title="__('Create Refund')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Refund {{ $sale->invoice_number }}"
            description="Pilih item dan quantity yang ingin direfund. Stok akan dikembalikan ke warehouse transaksi."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('cashier.sales.show', $sale) }}" variant="secondary">
                    Back to Invoice
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('cashier.sales.refunds.store', $sale) }}" id="refundForm" class="space-y-6">
            @csrf

            <div class="grid gap-4 md:grid-cols-3">
                <x-ui.stat-card
                    label="Invoice"
                    value="{{ $sale->invoice_number }}"
                    description="{{ $sale->sold_at?->format('d M Y H:i') }}"
                    tone="sky"
                >
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <path d="M14 2v6h6" />
                            <path d="M8 13h8" />
                            <path d="M8 17h5" />
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card
                    label="Cashier"
                    value="{{ $sale->cashier->name }}"
                    description="Handled transaction"
                    tone="slate"
                >
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21a8 8 0 1 0-16 0" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card
                    label="Warehouse"
                    value="{{ $sale->warehouse->name }}"
                    description="Stock return destination"
                    tone="green"
                >
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18" />
                            <path d="M4 21V8l8-5 8 5v13" />
                            <path d="M9 21v-8h6v8" />
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>
            </div>

            <x-ui.card padding="p-0" class="overflow-hidden">
                <div class="border-b border-slate-100 p-4 sm:p-5">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Refund Items</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Isi quantity refund pada item yang ingin dikembalikan.
                            </p>
                        </div>

                        <span class="inline-flex w-fit items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                            {{ $sale->items->count() }} items
                        </span>
                    </div>
                </div>

                <div class="hidden border-b border-slate-100 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600 lg:grid lg:grid-cols-[minmax(0,1.4fr)_repeat(4,minmax(0,0.75fr))_minmax(0,1fr)] lg:items-center sm:px-5">
                    <div>Product</div>
                    <div class="text-right">Sold Qty</div>
                    <div class="text-right">Refunded</div>
                    <div class="text-right">Available</div>
                    <div class="text-right">Refund Qty</div>
                    <div class="text-right">Unit Price</div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($sale->items as $index => $item)
                        @php
                            $refundedQty = (float) $item->refundItems->sum('quantity');
                            $availableQty = max((float) $item->quantity - $refundedQty, 0);
                        @endphp

                        <div class="p-4 transition hover:bg-sky-50/40 sm:p-5">
                            <input type="hidden"
                                   name="items[{{ $index }}][sale_item_id]"
                                   value="{{ $item->id }}">

                            <div class="grid gap-4 lg:grid-cols-[minmax(0,1.4fr)_repeat(4,minmax(0,0.75fr))_minmax(0,1fr)] lg:items-center">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900">
                                        {{ $item->product_name }}
                                    </div>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                        <span class="rounded-full bg-slate-100 px-2 py-1 font-mono">{{ $item->sku }}</span>
                                        <span>{{ $item->unit_name }}</span>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3 text-sm lg:bg-transparent lg:p-0 lg:text-right">
                                    <div class="text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Sold Qty</div>
                                    <div class="mt-1 font-semibold text-slate-700 lg:mt-0">
                                        {{ number_format($item->quantity, 2, ',', '.') }} {{ $item->unit_name }}
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3 text-sm lg:bg-transparent lg:p-0 lg:text-right">
                                    <div class="text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Refunded</div>
                                    <div class="mt-1 font-semibold text-slate-700 lg:mt-0">
                                        {{ number_format($refundedQty, 2, ',', '.') }} {{ $item->unit_name }}
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-sky-50 p-3 text-sm lg:bg-transparent lg:p-0 lg:text-right">
                                    <div class="text-xs font-medium uppercase tracking-wide text-sky-500 lg:hidden">Available</div>
                                    <div class="mt-1 font-bold {{ $availableQty > 0 ? 'text-sky-700' : 'text-red-600' }} lg:mt-0">
                                        {{ number_format($availableQty, 2, ',', '.') }} {{ $item->unit_name }}
                                    </div>
                                </div>

                                <div>
                                    <!-- <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400 lg:text-right">
                                        Refund Qty
                                    </label> -->
                                    <input type="number"
                                           name="items[{{ $index }}][quantity]"
                                           value="{{ old('items.' . $index . '.quantity', 0) }}"
                                           min="0"
                                           max="{{ $availableQty }}"
                                           step="0.01"
                                           data-price="{{ $item->unit_price }}"
                                           data-max="{{ $availableQty }}"
                                           class="refund-qty block w-full rounded-2xl border-slate-200 bg-slate-50 text-right text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100 lg:ml-auto lg:max-w-16 py-2"
                                           @disabled($availableQty <= 0)>
                                </div>

                                <div class="text-sm lg:text-right">
                                    <div class="text-xs font-medium uppercase tracking-wide text-slate-400 lg:hidden">Unit Price</div>
                                    <div class="mt-1 font-bold text-slate-900 lg:mt-0">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_380px]">
                <x-ui.card>
                    <div class="space-y-5">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Refund Info</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Pilih metode pengembalian dan isi alasan refund.
                            </p>
                        </div>

                        <div>
                            <label for="method" class="block text-sm font-semibold text-slate-700">
                                Refund Method
                            </label>
                            <select id="method"
                                    name="method"
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100 py-2">
                                <option value="cash" @selected(old('method') === 'cash')>Cash</option>
                                <option value="qris" @selected(old('method') === 'qris')>QRIS</option>
                                <option value="transfer" @selected(old('method') === 'transfer')>Transfer</option>
                                <option value="card" @selected(old('method') === 'card')>Card</option>
                            </select>

                            @error('method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-semibold text-slate-700">
                                Reason
                            </label>
                            <textarea id="reason"
                                      name="reason"
                                      rows="5"
                                      placeholder="Contoh: Barang rusak / customer salah beli"
                                      class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('reason') }}</textarea>

                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card class="lg:sticky lg:top-24 lg:self-start">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Refund Summary</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Nilai refund dihitung otomatis dari qty x harga jual item.
                        </p>
                    </div>

                    <div class="mt-5 rounded-3xl bg-gradient-to-br from-sky-50 to-cyan-50 p-5 ring-1 ring-sky-100">
                        <div class="text-sm font-medium text-sky-700">Estimated Refund</div>
                        <div id="refundTotalText" class="mt-2 text-3xl font-black tracking-tight text-sky-700">
                            Rp 0
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        Pastikan quantity refund sudah benar. Setelah disimpan, stok akan dikembalikan.
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                        <x-ui.link-button href="{{ route('cashier.sales.show', $sale) }}" variant="secondary">
                            Cancel
                        </x-ui.link-button>

                        <x-ui.button-primary type="submit" class="w-full">
                            Save Refund
                        </x-ui.button-primary>
                    </div>
                </x-ui.card>
            </div>
        </form>
    </div>

    <script>
        const qtyInputs = document.querySelectorAll('.refund-qty');
        const refundTotalText = document.getElementById('refundTotalText');
        const refundForm = document.getElementById('refundForm');

        function notifyToast(icon, title) {
            if (window.Toast) {
                Toast.fire({ icon, title });
                return;
            }

            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon,
                    title,
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
            }
        }

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value);
        }

        function calculateRefundTotal() {
            let total = 0;

            qtyInputs.forEach(input => {
                const quantity = Number(input.value || 0);
                const price = Number(input.dataset.price || 0);
                const max = Number(input.dataset.max || 0);

                if (quantity > max) {
                    input.value = max;
                    notifyToast('warning', 'Qty refund disesuaikan dengan batas tersedia.');
                }

                total += Number(input.value || 0) * price;
            });

            refundTotalText.textContent = formatRupiah(total);

            return total;
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateRefundTotal);
        });

        refundForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const total = calculateRefundTotal();

            if (total <= 0) {
                notifyToast('warning', 'Isi minimal satu qty refund lebih dari 0.');
                return;
            }

            if (!window.Swal) {
                refundForm.submit();
                return;
            }

            Swal.fire({
                title: 'Simpan refund?',
                text: 'Refund akan disimpan dan stok produk akan dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan refund',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    refundForm.submit();
                }
            });
        });

        calculateRefundTotal();
    </script>
</x-layouts.app>
