<x-layouts.app :title="__('Edit Warehouse')">
    <div class="p-6">
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Warehouse</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui data lokasi penyimpanan stok.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.warehouses.update', $warehouse) }}" class="space-y-5"
                      data-confirm-submit
                      data-confirm-title="Update warehouse?"
                      data-confirm-text="Perubahan data warehouse akan disimpan."
                      data-confirm-button="Ya, update"
                      data-confirm-icon="question">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Name
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $warehouse->name) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
                               autofocus>

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            Code
                        </label>
                        <input type="text"
                               id="code"
                               name="code"
                               autocomplete="off"
                               value="{{ old('code', $warehouse->code) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm uppercase focus:border-gray-900 focus:ring-gray-900">

                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Address
                        </label>
                        <textarea id="address"
                                  name="address"
                                  rows="4"
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('address', $warehouse->address) }}</textarea>

                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="is_default"
                                   id="is_default"
                                   value="1"
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                   @checked(old('is_default', $warehouse->is_default))>

                            <span class="text-sm text-gray-700">Set as default warehouse</span>
                        </label>

                        @error('is_default')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                   @checked(old('is_active', $warehouse->is_active))>

                            <span class="text-sm text-gray-700">Active</span>
                        </label>

                        @error('is_active')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.warehouses.index') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const codeInput = document.getElementById('code');
            const defaultCheckbox = document.getElementById('is_default');
            const activeCheckbox = document.getElementById('is_active');

            function fireToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            if (codeInput) {
                codeInput.addEventListener('input', function () {
                    codeInput.value = codeInput.value.toUpperCase().replace(/\s+/g, '-');
                });
            }

            if (defaultCheckbox) {
                defaultCheckbox.addEventListener('change', function () {
                    if (defaultCheckbox.checked) {
                        fireToast('info', 'Warehouse ini akan dijadikan default.');
                    } else {
                        fireToast('warning', 'Warehouse ini tidak lagi ditandai default.');
                    }
                });
            }

            if (activeCheckbox) {
                activeCheckbox.addEventListener('change', function () {
                    fireToast(
                        activeCheckbox.checked ? 'success' : 'warning',
                        activeCheckbox.checked ? 'Warehouse akan aktif.' : 'Warehouse akan nonaktif.'
                    );
                });
            }
        });
    </script>
</x-layouts.app>