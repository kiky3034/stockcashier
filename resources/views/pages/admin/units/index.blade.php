<x-layouts.app :title="__('Units')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Units</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola satuan produk StockCashier.
                </p>
            </div>

            <a href="{{ route('admin.units.create') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Add Unit
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.units.index') }}" class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari unit..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.units.index') }}"
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Abbreviation</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($units as $unit)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">
                                        {{ $unit->name }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono rounded bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-700">
                                            {{ $unit->abbreviation }}
                                        </span>

                                        <button type="button"
                                                class="copy-unit-abbreviation rounded-lg border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-abbreviation="{{ $unit->abbreviation }}">
                                            Copy
                                        </button>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    @if ($unit->is_active)
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
                                                class="show-unit-detail rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-name="{{ $unit->name }}"
                                                data-abbreviation="{{ $unit->abbreviation }}"
                                                data-status="{{ $unit->is_active ? 'Active' : 'Inactive' }}"
                                                data-created="{{ $unit->created_at?->format('d M Y H:i') }}">
                                            Detail
                                        </button>

                                        <a href="{{ route('admin.units.edit', $unit) }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Edit
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
                                                    class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada unit.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $units->links() }}
            </div>
        </div>
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
                            });
                        }
                    }
                });
            });

            document.querySelectorAll('.show-unit-detail').forEach(function (button) {
                button.addEventListener('click', function () {
                    const name = button.dataset.name || '-';
                    const abbreviation = button.dataset.abbreviation || '-';
                    const status = button.dataset.status || '-';
                    const created = button.dataset.created || '-';

                    if (!window.Swal) {
                        return;
                    }

                    window.Swal.fire({
                        title: 'Detail Unit',
                        html: `
                            <div class="text-left text-sm">
                                <div class="mb-2 flex justify-between gap-4">
                                    <span class="text-gray-500">Name</span>
                                    <span class="font-semibold text-gray-900">${name}</span>
                                </div>
                                <div class="mb-2 flex justify-between gap-4">
                                    <span class="text-gray-500">Abbreviation</span>
                                    <span class="font-mono font-semibold text-gray-900">${abbreviation}</span>
                                </div>
                                <div class="mb-2 flex justify-between gap-4">
                                    <span class="text-gray-500">Status</span>
                                    <span class="font-semibold text-gray-900">${status}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Created</span>
                                    <span class="font-semibold text-gray-900">${created}</span>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#111827',
                    });
                });
            });
        });
    </script>
</x-layouts.app>
