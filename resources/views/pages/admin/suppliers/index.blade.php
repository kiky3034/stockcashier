<x-layouts.app :title="__('Suppliers')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Suppliers</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola supplier untuk produk dan pembelian barang.
                </p>
            </div>

            <a href="{{ route('admin.suppliers.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Add Supplier
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari supplier..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.suppliers.index') }}"
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Phone</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $supplier->name }}
                                    </div>

                                    @if ($supplier->address)
                                        <div class="mt-1 max-w-md text-xs text-gray-500">
                                            {{ $supplier->address }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $supplier->phone ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $supplier->email ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    @if ($supplier->is_active)
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
                                                data-supplier-detail
                                                data-name="{{ e($supplier->name) }}"
                                                data-phone="{{ e($supplier->phone ?? '-') }}"
                                                data-email="{{ e($supplier->email ?? '-') }}"
                                                data-address="{{ e($supplier->address ?? '-') }}"
                                                data-status="{{ $supplier->is_active ? 'Active' : 'Inactive' }}"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Detail
                                        </button>

                                        <button type="button"
                                                data-copy-supplier
                                                data-copy-text="{{ e($supplier->name . ' | Phone: ' . ($supplier->phone ?? '-') . ' | Email: ' . ($supplier->email ?? '-')) }}"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Copy
                                        </button>

                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Edit
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
                                    Belum ada supplier.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $suppliers->links() }}
            </div>
        </div>
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
                        showConfirmButton: false
                    });
                }
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
                        title: name,
                        html: `
                            <div class="text-left text-sm">
                                <div class="mb-2"><strong>Phone:</strong> ${phone}</div>
                                <div class="mb-2"><strong>Email:</strong> ${email}</div>
                                <div class="mb-2"><strong>Status:</strong> ${status}</div>
                                <div><strong>Address:</strong><br>${address}</div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#111827'
                    });
                });
            });
        });
    </script>
</x-layouts.app>
