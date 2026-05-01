<x-layouts.app :title="__('Edit Supplier')">
    <div class="p-6">
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Supplier</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui data supplier.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST"
                      action="{{ route('admin.suppliers.update', $supplier) }}"
                      class="space-y-5"
                      data-confirm-submit
                      data-confirm-title="Update supplier?"
                      data-confirm-text="Perubahan data supplier akan disimpan."
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
                               value="{{ old('name', $supplier->name) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
                               autofocus>

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Phone
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $supplier->phone) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $supplier->email) }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('email')
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
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('address', $supplier->address) }}</textarea>

                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               data-supplier-active
                               class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                               @checked(old('is_active', $supplier->is_active))>

                        <span class="text-sm text-gray-700">Active</span>
                    </label>

                    @error('is_active')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.suppliers.index') }}"
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
            const activeInput = document.querySelector('[data-supplier-active]');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            activeInput?.addEventListener('change', function () {
                showToast(
                    this.checked ? 'info' : 'warning',
                    this.checked ? 'Supplier akan diaktifkan.' : 'Supplier akan dinonaktifkan.'
                );
            });

            phoneInput?.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
            });

            emailInput?.addEventListener('blur', function () {
                if (this.value && !this.checkValidity()) {
                    showToast('warning', 'Format email supplier belum valid.');
                }
            });
        });
    </script>
</x-layouts.app>
