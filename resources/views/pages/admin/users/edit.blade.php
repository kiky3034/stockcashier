<x-layouts.app :title="__('Edit User')">
    @php
        $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');

        $initials = $initials ?: 'U';
        $roleName = old('role', $currentRole);
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Edit User"
            description="Perbarui data user, password, dan role akses."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.users.index') }}" variant="secondary">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m12 19-7-7 7-7" />
                            <path d="M19 12H5" />
                        </svg>
                        Back to Users
                    </span>
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.users.update', $user) }}"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Update user?"
              data-confirm-text="Data user {{ $user->email }} akan diperbarui."
              data-confirm-button="Ya, update"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <x-ui.card padding="p-0">
                    <div class="border-b border-slate-100 p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sm font-black text-sky-700 ring-1 ring-sky-100">
                                {{ $initials }}
                            </div>

                            <div>
                                <h2 class="font-bold text-slate-900">User Information</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Data utama untuk akun pengguna.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   autofocus
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                            <p class="mt-2 text-xs text-slate-500">
                                Email dipakai untuk login ke StockCashier.
                            </p>

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card padding="p-0">
                    <div class="border-b border-slate-100 p-5">
                        <h2 class="font-bold text-slate-900">Security</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Kosongkan password jika tidak ingin mengganti password user.
                        </p>
                    </div>

                    <div class="p-5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">
                            New Password
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               minlength="8"
                               placeholder="Kosongkan jika tidak ingin ganti password"
                               class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">

                        <div class="mt-3 rounded-2xl border border-sky-100 bg-sky-50 p-3 text-xs leading-5 text-sky-700">
                            Password baru minimal 8 karakter. Jika field ini kosong, password lama tetap digunakan.
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.card>

                <x-ui.card padding="p-0">
                    <div class="border-b border-slate-100 p-5">
                        <h2 class="font-bold text-slate-900">Role Access</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Tentukan area aplikasi yang bisa diakses user.
                        </p>
                    </div>

                    <div class="p-5">
                        <label for="role" class="block text-sm font-semibold text-slate-700">Role</label>
                        <select id="role"
                                name="role"
                                required
                                class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                            <option value="">- Select Role -</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role', $currentRole) === $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Current Role</div>
                                <div class="mt-2 text-sm font-bold text-slate-900">
                                    {{ $currentRole ? str_replace('_', ' ', ucwords($currentRole, '_')) : 'No Role' }}
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Selected Role</div>
                                <div class="mt-2 text-sm font-bold text-sky-700" id="selectedRolePreview">
                                    {{ $roleName ? str_replace('_', ' ', ucwords($roleName, '_')) : 'Not selected' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-400 to-cyan-400 text-2xl font-black text-white shadow-sm shadow-sky-200">
                            {{ $initials }}
                        </div>

                        <div class="mt-4">
                            <h3 class="font-bold text-slate-900">{{ $user->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                        </div>

                        <div class="mt-4 inline-flex rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                            {{ $currentRole ? str_replace('_', ' ', ucwords($currentRole, '_')) : 'No Role' }}
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 border-t border-slate-100 pt-5 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">User ID</span>
                            <span class="font-semibold text-slate-900">#{{ $user->id }}</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Created</span>
                            <span class="text-right font-semibold text-slate-900">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Updated</span>
                            <span class="text-right font-semibold text-slate-900">
                                {{ $user->updated_at?->format('d M Y') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h3 class="font-bold text-slate-900">Before Updating</h3>

                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                            Pastikan email belum digunakan user lain.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                            Ganti password hanya jika benar-benar diperlukan.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
                            Role menentukan menu dan akses halaman user.
                        </li>
                    </ul>
                </x-ui.card>

                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="grid gap-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Update User
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.users.index') }}" variant="secondary" class="w-full">
                            Cancel
                        </x-ui.link-button>
                    </div>
                </div>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const roleSelect = document.getElementById('role');
            const selectedRolePreview = document.getElementById('selectedRolePreview');

            function showToast(icon, title) {
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
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            }

            function formatRole(role) {
                if (!role) {
                    return 'Not selected';
                }

                return role
                    .replaceAll('_', ' ')
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }

            emailInput?.addEventListener('blur', function () {
                if (emailInput.value && !emailInput.checkValidity()) {
                    showToast('warning', 'Format email belum valid');
                }
            });

            passwordInput?.addEventListener('blur', function () {
                if (passwordInput.value && passwordInput.value.length < 8) {
                    showToast('warning', 'Password baru minimal 8 karakter');
                    return;
                }

                if (passwordInput.value.length >= 8) {
                    showToast('info', 'Password user akan diganti saat update');
                }
            });

            roleSelect?.addEventListener('change', function () {
                if (selectedRolePreview) {
                    selectedRolePreview.textContent = formatRole(roleSelect.value);
                }

                if (roleSelect.value) {
                    showToast('info', `Role dipilih: ${roleSelect.value}`);
                }
            });
        });
    </script>
</x-layouts.app>
