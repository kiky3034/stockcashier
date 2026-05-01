<x-layouts.app :title="__('Warehouses')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Warehouses</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola lokasi penyimpanan stok.
                </p>
            </div>

            <a href="{{ route('admin.warehouses.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Add Warehouse
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.warehouses.index') }}" class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari warehouse..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.warehouses.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Code</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Default</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($warehouses as $warehouse)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $warehouse->name }}
                                    </div>

                                    @if ($warehouse->address)
                                        <div class="mt-1 max-w-md text-xs text-gray-500">
                                            {{ $warehouse->address }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono">{{ $warehouse->code }}</span>

                                        <button type="button"
                                                class="rounded border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50"
                                                data-copy-warehouse-code="{{ $warehouse->code }}">
                                            Copy
                                        </button>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($warehouse->is_default)
                                        <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">
                                            Default
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($warehouse->is_active)
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-warehouse-detail
                                                data-name="{{ $warehouse->name }}"
                                                data-code="{{ $warehouse->code }}"
                                                data-address="{{ $warehouse->address ?: '-' }}"
                                                data-default="{{ $warehouse->is_default ? 'Yes' : 'No' }}"
                                                data-status="{{ $warehouse->is_active ? 'Active' : 'Inactive' }}">
                                            Detail
                                        </button>

                                        <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.warehouses.destroy', $warehouse) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus warehouse?"
                                              data-confirm-text="Warehouse {{ $warehouse->name }} akan dihapus jika belum digunakan transaksi/stok."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada warehouse.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $warehouses->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function fireToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            document.querySelectorAll('[data-copy-warehouse-code]').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const code = button.dataset.copyWarehouseCode;

                    try {
                        await navigator.clipboard.writeText(code);
                        fireToast('success', 'Kode warehouse disalin.');
                    } catch (error) {
                        fireToast('error', 'Gagal menyalin kode warehouse.');
                    }
                });
            });

            document.querySelectorAll('[data-warehouse-detail]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const html = `
                        <div class="text-left text-sm">
                            <div class="mb-2"><strong>Name:</strong> ${escapeHtml(button.dataset.name)}</div>
                            <div class="mb-2"><strong>Code:</strong> <code>${escapeHtml(button.dataset.code)}</code></div>
                            <div class="mb-2"><strong>Address:</strong> ${escapeHtml(button.dataset.address)}</div>
                            <div class="mb-2"><strong>Default:</strong> ${escapeHtml(button.dataset.default)}</div>
                            <div><strong>Status:</strong> ${escapeHtml(button.dataset.status)}</div>
                        </div>
                    `;

                    if (window.Swal) {
                        Swal.fire({
                            title: 'Warehouse Detail',
                            html: html,
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#111827'
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>