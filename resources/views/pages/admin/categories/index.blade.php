<x-layouts.app :title="__('Categories')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Categories"
            description="Kelola kategori produk StockCashier agar katalog produk lebih rapi."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.categories.create') }}">
                    + Add Category
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <x-ui.stat-card
                label="Total Categories"
                value="{{ number_format($categories->total(), 0, ',', '.') }}"
                description="Jumlah kategori yang cocok dengan filter saat ini."
                tone="sky"
            />

            <x-ui.stat-card
                label="Search Status"
                value="{{ $search ? 'Filtered' : 'All' }}"
                description="{{ $search ? 'Menampilkan hasil pencarian.' : 'Menampilkan semua kategori.' }}"
                tone="slate"
            />

            <x-ui.stat-card
                label="Master Data"
                value="Category"
                description="Dipakai untuk pengelompokan produk di POS dan laporan."
                tone="green"
            />
        </div>

        <x-ui.card padding="p-0">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_auto_auto]">
                    <div>
                        <label for="search" class="sr-only">Search category</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari category..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.categories.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Name</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Slug</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-3 text-right text-sm font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $category->name }}</div>
                                    @if ($category->description)
                                        <div class="mt-1 max-w-xl text-xs leading-5 text-slate-500">
                                            {{ $category->description }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-xl bg-slate-100 px-2.5 py-1 font-mono text-xs font-semibold text-slate-600">
                                        {{ $category->slug }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($category->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           title="Edit category"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.categories.destroy', $category) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus category?"
                                              data-confirm-text="Category {{ $category->name }} akan dihapus jika belum digunakan oleh produk."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Delete category"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-500">
                                    Belum ada category.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-3 p-4 lg:hidden">
                @forelse ($categories as $category)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900">{{ $category->name }}</div>
                                <div class="mt-1 font-mono text-xs text-slate-500">{{ $category->slug }}</div>
                            </div>

                            @if ($category->is_active)
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Active</span>
                            @else
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">Inactive</span>
                            @endif
                        </div>

                        @if ($category->description)
                            <p class="mt-3 text-sm leading-6 text-slate-500">{{ $category->description }}</p>
                        @endif

                        <div class="mt-4 flex justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $category) }}"
                                  data-confirm-submit
                                  data-confirm-title="Hapus category?"
                                  data-confirm-text="Category {{ $category->name }} akan dihapus jika belum digunakan oleh produk."
                                  data-confirm-button="Ya, hapus"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        Belum ada category.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $categories->links() }}
            </div>
        </x-ui.card>
    </div>
</x-layouts.app>
