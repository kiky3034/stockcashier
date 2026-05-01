<x-layouts.app :title="__('Units')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Units"
            description="Kelola satuan produk yang digunakan pada StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.units.create') }}">
                    + Add Unit
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Total Units"
                value="{{ number_format(method_exists($units, 'total') ? $units->total() : $units->count(), 0, ',', '.') }}"
                description="Jumlah satuan yang tersedia"
                tone="sky"
            />

            <x-ui.stat-card
                label="Active Units"
                value="{{ number_format($units->where('is_active', true)->count(), 0, ',', '.') }}"
                description="Aktif pada halaman ini"
                tone="green"
            />

            <x-ui.stat-card
                label="Inactive Units"
                value="{{ number_format($units->where('is_active', false)->count(), 0, ',', '.') }}"
                description="Nonaktif pada halaman ini"
                tone="slate"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.units.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto]">
                    <div>
                        <label for="search" class="sr-only">Search units</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari unit atau abbreviation..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <x-ui.button-primary type="submit">
                        Search
                    </x-ui.button-primary>

                    @if ($search)
                        <x-ui.link-button href="{{ route('admin.units.index') }}" variant="secondary">
                            Reset
                        </x-ui.link-button>
                    @endif
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Name</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Abbreviation</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-3 text-left text-sm font-semibold text-slate-700">Created</th>
                            <th class="px-5 py-3 text-right text-sm font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($units as $unit)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $unit->name }}</div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-xl bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                            {{ $unit->abbreviation }}
                                        </span>

                                        <button type="button"
                                                class="copy-unit-abbreviation inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-abbreviation="{{ $unit->abbreviation }}"
                                                title="Copy abbreviation">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($unit->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    {{ $unit->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                class="show-unit-detail inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-name="{{ $unit->name }}"
                                                data-abbreviation="{{ $unit->abbreviation }}"
                                                data-status="{{ $unit->is_active ? 'Active' : 'Inactive' }}"
                                                data-created="{{ $unit->created_at?->format('d M Y H:i') }}"
                                                title="Detail">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.units.edit', $unit) }}"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                           title="Edit">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.units.destroy', $unit) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus unit?"
                                              data-confirm-text="Unit {{ $unit->name }} akan dihapus jika belum digunakan pada produk."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50"
                                                    title="Delete">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-500 ring-1 ring-sky-100">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 7h18"></path>
                                                <path d="M3 12h18"></path>
                                                <path d="M3 17h18"></path>
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 font-semibold text-slate-900">Belum ada unit</h3>
                                        <p class="mt-1 text-sm text-slate-500">Tambahkan unit pertama untuk mulai mengatur satuan produk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid gap-3 p-4 lg:hidden">
                @forelse ($units as $unit)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $unit->name }}</div>
                                <div class="mt-2 inline-flex items-center rounded-xl bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                    {{ $unit->abbreviation }}
                                </div>
                            </div>

                            @if ($unit->is_active)
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 grid grid-cols-4 gap-2">
                            <button type="button"
                                    class="copy-unit-abbreviation inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-slate-600"
                                    data-abbreviation="{{ $unit->abbreviation }}"
                                    title="Copy">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </button>

                            <button type="button"
                                    class="show-unit-detail inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-slate-600"
                                    data-name="{{ $unit->name }}"
                                    data-abbreviation="{{ $unit->abbreviation }}"
                                    data-status="{{ $unit->is_active ? 'Active' : 'Inactive' }}"
                                    data-created="{{ $unit->created_at?->format('d M Y H:i') }}"
                                    title="Detail">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>

                            <a href="{{ route('admin.units.edit', $unit) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-slate-600"
                               title="Edit">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                </svg>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.units.destroy', $unit) }}"
                                  data-confirm-submit
                                  data-confirm-title="Hapus unit?"
                                  data-confirm-text="Unit {{ $unit->name }} akan dihapus jika belum digunakan pada produk."
                                  data-confirm-button="Ya, hapus"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl border border-red-200 px-3 py-2 text-red-600"
                                        title="Delete">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18"></path>
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        Belum ada unit.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $units->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showToast = (options) => {
                if (window.Toast) {
                    window.Toast.fire(options);
                    return;
                }

                if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        ...options,
                    });
                }
            };

            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            document.querySelectorAll('.copy-unit-abbreviation').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const abbreviation = button.dataset.abbreviation || '';

                    try {
                        await navigator.clipboard.writeText(abbreviation);

                        showToast({
                            icon: 'success',
                            title: `Abbreviation ${abbreviation} disalin`,
                        });
                    } catch (error) {
                        if (window.Swal) {
                            window.Swal.fire({
                                icon: 'error',
                                title: 'Gagal menyalin',
                                text: 'Browser tidak mengizinkan akses clipboard.',
                                confirmButtonColor: '#0ea5e9',
                            });
                        }
                    }
                });
            });

            document.querySelectorAll('.show-unit-detail').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = escapeHtml(button.dataset.name || '-');
                    const abbreviation = escapeHtml(button.dataset.abbreviation || '-');
                    const status = escapeHtml(button.dataset.status || '-');
                    const created = escapeHtml(button.dataset.created || '-');

                    if (!window.Swal) {
                        return;
                    }

                    window.Swal.fire({
                        title: 'Detail Unit',
                        html: `
                            <div class="space-y-3 text-left text-sm">
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Name</div>
                                    <div class="mt-1 font-semibold text-slate-900">${name}</div>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Abbreviation</div>
                                    <div class="mt-1 font-mono font-semibold text-slate-900">${abbreviation}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</div>
                                        <div class="mt-1 font-semibold text-slate-900">${status}</div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 p-3">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Created</div>
                                        <div class="mt-1 font-semibold text-slate-900">${created}</div>
                                    </div>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9',
                    });
                });
            });
        });
    </script>
</x-layouts.app>
