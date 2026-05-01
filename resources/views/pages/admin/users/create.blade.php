<x-layouts.app :title="__('Create User')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Add User"
            description="Tambahkan user baru dan assign role untuk akses StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.users.index') }}" variant="secondary">
                    Back to Users
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.users.store') }}"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Simpan user baru?"
              data-confirm-text="User baru akan dibuat dan role akan langsung diberikan."
              data-confirm-button="Ya, simpan"
              data-confirm-icon="question">
            @csrf

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-bold text-slate-900">User Information</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Data dasar untuk akun user baru.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">
                                Name
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Contoh: John Doe"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-semibold text-slate-700">
                                Email
                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="user@stockcashier.test"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-bold text-slate-900">Security & Role</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Tentukan password awal dan role untuk user baru.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                Password
                            </label>

                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Minimal 8 karakter"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-slate-500">
                                Gunakan minimal 8 karakter untuk keamanan akun.
                            </p>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-semibold text-slate-700">
                                Role
                            </label>

                            <select id="role"
                                    name="role"
                                    required
                                    class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                                <option value="">- Select Role -</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-slate-500">
                                Role menentukan menu dan fitur yang bisa diakses.
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-100 to-cyan-100 text-2xl font-black text-sky-700 ring-1 ring-sky-200">
                            <span id="userInitialPreview">U</span>
                        </div>

                        <h2 class="mt-4 text-lg font-bold text-slate-900" id="userNamePreview">
                            New User
                        </h2>

                        <p class="mt-1 max-w-xs text-sm text-slate-500" id="userEmailPreview">
                            Email belum diisi.
                        </p>

                        <div class="mt-4 inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100" id="rolePreview">
                            No role selected
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h3 class="font-bold text-slate-900">Access Notes</h3>

                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="rounded-2xl bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">Admin</div>
                            <p class="mt-1 text-xs text-slate-500">Mengelola master data, user, settings, dan audit.</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">Owner</div>
                            <p class="mt-1 text-xs text-slate-500">Mengakses dashboard dan laporan bisnis.</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">Cashier</div>
                            <p class="mt-1 text-xs text-slate-500">Mengakses POS, sales history, dan refund.</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-3">
                            <div class="font-semibold text-slate-800">Warehouse Staff</div>
                            <p class="mt-1 text-xs text-slate-500">Mengelola stok, purchase, dan stock movement.</p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Save User
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full">
                            Cancel
                        </x-ui.link-button>
                    </div>
                </x-ui.card>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const roleSelect = document.getElementById('role');

            const initialPreview = document.getElementById('userInitialPreview');
            const namePreview = document.getElementById('userNamePreview');
            const emailPreview = document.getElementById('userEmailPreview');
            const rolePreview = document.getElementById('rolePreview');

            function toast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon,
                        title,
                        showConfirmButton: false,
                        timer: 2200,
                        timerProgressBar: true
                    });
                }
            }

            function updatePreview() {
                const name = nameInput.value.trim();
                const email = emailInput.value.trim();
                const role = roleSelect.value;

                initialPreview.textContent = name ? name.charAt(0).toUpperCase() : 'U';
                namePreview.textContent = name || 'New User';
                emailPreview.textContent = email || 'Email belum diisi.';
                rolePreview.textContent = role || 'No role selected';
            }

            nameInput?.addEventListener('input', updatePreview);
            emailInput?.addEventListener('input', updatePreview);
            roleSelect?.addEventListener('change', function () {
                updatePreview();

                if (roleSelect.value) {
                    toast('info', `Role dipilih: ${roleSelect.value}`);
                }
            });

            emailInput?.addEventListener('blur', function () {
                const value = emailInput.value.trim();

                if (value && !value.includes('@')) {
                    toast('warning', 'Format email belum valid.');
                }
            });

            passwordInput?.addEventListener('blur', function () {
                const value = passwordInput.value;

                if (value && value.length < 8) {
                    toast('warning', 'Password minimal 8 karakter.');
                }
            });

            updatePreview();
        });
    </script>
</x-layouts.app>
