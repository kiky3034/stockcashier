<x-layouts.app :title="__('Create User')">
    <div class="p-6">
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add User</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Tambahkan user baru dan assign role.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST"
                      action="{{ route('admin.users.store') }}"
                      class="space-y-5"
                      data-confirm-submit
                      data-confirm-title="Simpan user baru?"
                      data-confirm-text="User baru akan dibuat dan role akan langsung diberikan."
                      data-confirm-button="Ya, simpan"
                      data-confirm-icon="question">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
                               autofocus>

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               minlength="8"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        <p class="mt-1 text-xs text-gray-500">
                            Minimal 8 karakter.
                        </p>

                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role"
                                name="role"
                                required
                                class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                            <option value="">- Select Role -</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.users.index') }}"
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
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const roleSelect = document.getElementById('role');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            emailInput?.addEventListener('blur', function () {
                if (emailInput.value && !emailInput.checkValidity()) {
                    showToast('warning', 'Format email belum valid');
                }
            });

            passwordInput?.addEventListener('blur', function () {
                if (passwordInput.value && passwordInput.value.length < 8) {
                    showToast('warning', 'Password minimal 8 karakter');
                }
            });

            roleSelect?.addEventListener('change', function () {
                if (roleSelect.value) {
                    showToast('info', `Role dipilih: ${roleSelect.value}`);
                }
            });
        });
    </script>
</x-layouts.app>
