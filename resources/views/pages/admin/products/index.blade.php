<x-layouts.app :title="__('Products')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Products"
            description="Kelola produk, harga, gambar, barcode, dan status stok."
        >
            <x-slot:actions>
                {{-- Kosongkan actions karena tombol sudah dipindahkan --}}
            </x-slot:actions>
        </x-page-header>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari produk, SKU, atau barcode..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Search
                        </button>

                        @if ($search)
                            <a href="{{ route('admin.products.index') }}"
                               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                Reset
                            </a>
                        @endif

                        {{-- Tombol Add Product di sini --}}
                        <a href="{{ route('admin.products.create') }}"
                           class="inline-flex items-center justify-center gap-1 rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Add Product
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Product</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Category</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Price</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Stock</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-700">Status</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($products as $product)
                            @php
                                $totalStock = $product->stocks->sum('quantity');
                                $isLowStock = $product->track_stock && $totalStock <= $product->stock_alert_level;
                            @endphp

                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-3 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        @if ($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                 alt="{{ $product->name }}"
                                                 class="h-10 w-10 rounded-xl border border-slate-200 object-cover shadow-sm">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 text-[9px] font-semibold uppercase tracking-wide text-slate-400">
                                                No Img
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900 text-sm">
                                                {{ $product->name }}
                                            </div>

                                            <div class="mt-0.5 space-y-0.5 text-xs text-slate-500">
                                                <div>SKU: <span class="font-mono text-slate-700">{{ $product->sku }}</span></div>
                                                @if ($product->barcode)
                                                    <div>Barcode: <span class="font-mono text-slate-700">{{ $product->barcode }}</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-3 py-3 align-top text-xs text-slate-600">
                                    {{ $product->category?->name ?? '-' }}
                                </td>

                                <td class="px-3 py-3 align-top text-xs text-slate-600">
                                    <div>Buy: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</div>
                                    <div class="mt-0.5 font-semibold text-slate-900">Sell: Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                </td>

                                <td class="px-3 py-3 align-top text-xs text-slate-600">
                                    @if ($product->track_stock)
                                        <span class="font-semibold {{ $isLowStock ? 'text-red-600' : 'text-slate-900' }}">
                                            {{ number_format($totalStock, 2, ',', '.') }}
                                            {{ $product->unit?->abbreviation }}
                                        </span>

                                        @if ($isLowStock)
                                            <div class="mt-0.5 text-xs font-semibold text-red-600">
                                                Low stock
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-400">Not tracked</span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 align-top">
                                    @if ($product->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-3 align-top">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           title="Edit product"
                                           aria-label="Edit product"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-sky-200 bg-sky-50 text-sky-700 transition hover:bg-sky-100 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.products.destroy', $product) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus produk?"
                                              data-confirm-text="Produk {{ $product->name }} akan dihapus jika belum memiliki stok, transaksi, atau stock movement."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Delete product"
                                                    aria-label="Delete product"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-100">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4h8v2" />
                                                    <path d="M19 6l-1 14H6L5 6" />
                                                    <path d="M10 11v6" />
                                                    <path d="M14 11v6" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-10 text-center text-sm text-slate-500">
                                    Belum ada product.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid gap-3 p-4 lg:hidden">
                @forelse ($products as $product)
                    @php
                        $totalStock = $product->stocks->sum('quantity');
                        $isLowStock = $product->track_stock && $totalStock <= $product->stock_alert_level;
                    @endphp

                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex gap-2">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                     alt="{{ $product->name }}"
                                     class="h-12 w-12 rounded-xl border border-slate-200 object-cover shadow-sm">
                            @else
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 text-[9px] font-semibold uppercase tracking-wide text-slate-400">
                                    No Img
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-slate-900 text-sm">{{ $product->name }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">SKU: <span class="font-mono text-slate-700">{{ $product->sku }}</span></div>
                                @if ($product->barcode)
                                    <div class="mt-0.5 text-xs text-slate-500">Barcode: <span class="font-mono text-slate-700">{{ $product->barcode }}</span></div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <div class="font-semibold text-slate-400">Category</div>
                                <div class="mt-0.5 text-slate-600">{{ $product->category?->name ?? '-' }}</div>
                            </div>

                            <div>
                                <div class="font-semibold text-slate-400">Stock</div>
                                <div class="mt-0.5 {{ $isLowStock ? 'font-semibold text-red-600' : 'text-slate-700' }}">
                                    @if ($product->track_stock)
                                        {{ number_format($totalStock, 2, ',', '.') }} {{ $product->unit?->abbreviation }}
                                    @else
                                        Not tracked
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="font-semibold text-slate-400">Buy Price</div>
                                <div class="mt-0.5 text-slate-600">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</div>
                            </div>

                            <div>
                                <div class="font-semibold text-slate-400">Sell Price</div>
                                <div class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-2 border-t border-slate-100 pt-3">
                            @if ($product->is_active)
                                <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                    Inactive
                                </span>
                            @endif

                            <div class="flex gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   title="Edit product"
                                   aria-label="Edit product"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-sky-200 bg-sky-50 text-sky-700 transition hover:bg-sky-100">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      data-confirm-submit
                                      data-confirm-title="Hapus produk?"
                                      data-confirm-text="Produk {{ $product->name }} akan dihapus jika belum memiliki stok, transaksi, atau stock movement."
                                      data-confirm-button="Ya, hapus"
                                      data-confirm-icon="warning">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            title="Delete product"
                                            aria-label="Delete product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                        Belum ada product.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 sm:p-5">
                {{ $products->links() }}
            </div>
        </x-ui.card>
    </div>
</x-layouts.app>