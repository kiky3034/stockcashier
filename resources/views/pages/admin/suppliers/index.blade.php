<x-layouts.app :title="__('Suppliers')">
    @php
        $supplierCollection = $suppliers instanceof \Illuminate\Pagination\AbstractPaginator ? $suppliers->getCollection() : collect($suppliers);
        $activeSuppliers = $supplierCollection->where('is_active', true)->count();
        $inactiveSuppliers = $supplierCollection->where('is_active', false)->count();
    @endphp

    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Suppliers"
            description="Kelola supplier untuk produk dan pembelian barang."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.suppliers.create') }}">
                    + Add Supplier
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Suppliers on Page"
                value="{{ number_format($supplierCollection->count(), 0, ',', '.') }}"
                description="Data yang tampil pada halaman ini"
                tone="sky"
            />

            <x-ui.stat-card
                label="Active"
                value="{{ number_format($activeSuppliers, 0, ',', '.') }}"
                description="Supplier aktif pada halaman ini"
                tone="green"
            />

            <x-ui.stat-card
                label="Inactive"
                value="{{ number_format($inactiveSuppliers, 0, ',', '.') }}"
                description="Supplier nonaktif pada halaman ini"
                tone="slate"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.suppliers.index') }}" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_auto_auto]">
                    <div>
                        <label for="search" class="sr-only">Search supplier</label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari supplier, phone, email, atau address..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.suppliers.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Supplier</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Phone</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Email</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-5 py-3 text-right font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($suppliers as $supplier)
                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sm font-bold text-sky-700 ring-1 ring-sky-100">
                                            {{ strtoupper(str($supplier->name)->substr(0, 2)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900">
                                                {{ $supplier->name }}
                                            </div>

                                            @if ($supplier->address)
                                                <div class="mt-1 max-w-md truncate text-xs text-slate-500">
                                                    {{ $supplier->address }}
                                                </div>
                                            @else
                                                <div class="mt-1 text-xs text-slate-400">No address</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $supplier->phone ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $supplier->email ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($supplier->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                title="Detail"
                                                data-supplier-detail
                                                data-name="{{ e($supplier->name) }}"
                                                data-phone="{{ e($supplier->phone ?? '-') }}"
                                                data-email="{{ e($supplier->email ?? '-') }}"
                                                data-address="{{ e($supplier->address ?? '-') }}"
                                                data-status="{{ $supplier->is_active ? 'Active' : 'Inactive' }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <button type="button"
                                                title="Copy"
                                                data-copy-supplier
                                                data-copy-text="{{ e($supplier->name . ' | Phone: ' . ($supplier->phone ?? '-') . ' | Email: ' . ($supplier->email ?? '-')) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                                <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                           title="Edit"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus supplier?"
                                              data-confirm-text="Supplier {{ $supplier->name }} akan dihapus jika belum digunakan dalam produk atau purchase."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Delete"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-white text-red-600 transition hover:bg-red-50">
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
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-500 ring-1 ring-sky-100">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <div class="mt-4 font-semibold text-slate-900">Belum ada supplier</div>
                                    <p class="mt-1 text-sm text-slate-500">Tambahkan supplier pertama untuk mulai mengelola pembelian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($suppliers as $supplier)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-start gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sm font-bold text-sky-700 ring-1 ring-sky-100">
                                    {{ strtoupper(str($supplier->name)->substr(0, 2)) }}
                                </div>

                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900">{{ $supplier->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $supplier->phone ?? '-' }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $supplier->email ?? '-' }}</div>
                                </div>
                            </div>

                            @if ($supplier->is_active)
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Active</span>
                            @else
                                <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">Inactive</span>
                            @endif
                        </div>

                        @if ($supplier->address)
                            <div class="mt-3 rounded-2xl bg-slate-50 p-3 text-xs text-slate-500">
                                {{ $supplier->address }}
                            </div>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button"
                                    data-supplier-detail
                                    data-name="{{ e($supplier->name) }}"
                                    data-phone="{{ e($supplier->phone ?? '-') }}"
                                    data-email="{{ e($supplier->email ?? '-') }}"
                                    data-address="{{ e($supplier->address ?? '-') }}"
                                    data-status="{{ $supplier->is_active ? 'Active' : 'Inactive' }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" /></svg>
                            </button>

                            <button type="button"
                                    data-copy-supplier
                                    data-copy-text="{{ e($supplier->name . ' | Phone: ' . ($supplier->phone ?? '-') . ' | Email: ' . ($supplier->email ?? '-')) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2" /><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" /></svg>
                            </button>

                            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9" /><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" /></svg>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                  data-confirm-submit
                                  data-confirm-title="Hapus supplier?"
                                  data-confirm-text="Supplier {{ $supplier->name }} akan dihapus jika belum digunakan dalam produk atau purchase."
                                  data-confirm-button="Ya, hapus"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-white text-red-600">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M8 6V4h8v2" /><path d="M19 6l-1 14H6L5 6" /><path d="M10 11v6" /><path d="M14 11v6" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">Belum ada supplier.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 sm:p-5">
                {{ $suppliers->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showToast = function (icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        timer: 1800,
                        showConfirmButton: false,
                        confirmButtonColor: '#0ea5e9'
                    });
                }
            };

            const escapeHtml = function (value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            };

            document.querySelectorAll('[data-copy-supplier]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const text = button.dataset.copyText || '';

                    try {
                        await navigator.clipboard.writeText(text);
                        showToast('success', 'Data supplier disalin');
                    } catch (error) {
                        showToast('error', 'Gagal menyalin data supplier');
                    }
                });
            });

            document.querySelectorAll('[data-supplier-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = button.dataset.name || '-';
                    const phone = button.dataset.phone || '-';
                    const email = button.dataset.email || '-';
                    const address = button.dataset.address || '-';
                    const status = button.dataset.status || '-';

                    if (!window.Swal) {
                        alert(`${name}\nPhone: ${phone}\nEmail: ${email}\nStatus: ${status}`);
                        return;
                    }

                    Swal.fire({
                        title: escapeHtml(name),
                        html: `
                            <div class="space-y-3 text-left text-sm text-slate-700">
                                <div class="rounded-2xl bg-slate-50 p-3"><strong>Phone:</strong> ${escapeHtml(phone)}</div>
                                <div class="rounded-2xl bg-slate-50 p-3"><strong>Email:</strong> ${escapeHtml(email)}</div>
                                <div class="rounded-2xl bg-slate-50 p-3"><strong>Status:</strong> ${escapeHtml(status)}</div>
                                <div class="rounded-2xl bg-slate-50 p-3"><strong>Address:</strong><br>${escapeHtml(address)}</div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9'
                    });
                });
            });
        });
    </script>
</x-layouts.app>
