<x-layouts.app :title="__('Create Unit')">
    <div class="p-6">
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Unit</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Tambahkan satuan produk baru.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST"
                      action="{{ route('admin.units.store') }}"
                      class="space-y-5"
                      data-confirm-submit
                      data-confirm-title="Simpan unit baru?"
                      data-confirm-text="Unit baru akan ditambahkan ke master data satuan produk."
                      data-confirm-button="Ya, simpan unit"
                      data-confirm-icon="question">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Name
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Piece"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
                               autofocus>

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="abbreviation" class="block text-sm font-medium text-gray-700">
                            Abbreviation
                        </label>
                        <input type="text"
                               id="abbreviation"
                               name="abbreviation"
                               value="{{ old('abbreviation') }}"
                               placeholder="Contoh: pcs"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('abbreviation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               id="unitActiveToggle"
                               name="is_active"
                               value="1"
                               class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                               @checked(old('is_active', true))>

                        <span class="text-sm text-gray-700">Active</span>
                    </label>

                    @error('is_active')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.units.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activeToggle = document.getElementById('unitActiveToggle');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            if (activeToggle) {
                activeToggle.addEventListener('change', function () {
                    showToast(
                        this.checked ? 'info' : 'warning',
                        this.checked ? 'Unit akan aktif setelah disimpan.' : 'Unit akan nonaktif setelah disimpan.'
                    );
                });
            }
        });
    </script>
</x-layouts.app>
