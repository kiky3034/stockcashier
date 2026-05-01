<x-layouts.app :title="__('Users')">
    @php
        $totalUsers = method_exists($users, 'total') ? $users->total() : $users->count();
        $displayedUsers = $users->count();
        $usersWithRole = $users->filter(fn ($user) => $user->roles->isNotEmpty())->count();
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Users"
            description="Kelola user dan role StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.users.create') }}">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Add User
                    </span>
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Total Users"
                value="{{ number_format($totalUsers, 0, ',', '.') }}"
                description="Semua user terdaftar"
                tone="sky"
            />

            <x-ui.stat-card
                label="Displayed"
                value="{{ number_format($displayedUsers, 0, ',', '.') }}"
                description="User di halaman ini"
                tone="slate"
            />

            <x-ui.stat-card
                label="Assigned Role"
                value="{{ number_format($usersWithRole, 0, ',', '.') }}"
                description="User dengan role di halaman ini"
                tone="green"
            />
        </div>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="border-b border-slate-100 bg-white p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </span>

                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama atau email user..."
                               class="block w-full rounded-2xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Search
                        </button>

                        @if ($search)
                            <a href="{{ route('admin.users.index') }}"
                               class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-5 py-3 text-center text-sm font-semibold text-slate-600">User</th>
                            <th class="px-5 py-3 text-center text-sm font-semibold text-slate-600">Role</th>
                            <th class="px-5 py-3 text-center text-sm font-semibold text-slate-600">Created</th>
                            <th class="px-5 py-3 text-center text-sm font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($users as $user)
                            @php
                                $roleNames = $user->roles->pluck('name')->values()->all();
                                $roleText = implode(', ', $roleNames) ?: '-';
                                $initials = collect(explode(' ', $user->name))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('');
                            @endphp

                            <tr class="transition hover:bg-sky-50/40">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sm font-bold uppercase text-sky-700 ring-1 ring-sky-100">
                                            {{ $initials ?: 'U' }}
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                            <div class="mt-1 truncate text-xs text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($user->roles as $role)
                                            <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-500 ring-1 ring-slate-100">
                                                No role
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $user->created_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                title="Detail"
                                                aria-label="Detail"
                                                class="user-detail-button inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-name="{{ e($user->name) }}"
                                                data-email="{{ e($user->email) }}"
                                                data-roles="{{ e($roleText) }}"
                                                data-created="{{ $user->created_at?->format('d M Y H:i') }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <button type="button"
                                                title="Copy Email"
                                                aria-label="Copy Email"
                                                class="copy-email-button inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                                data-email="{{ e($user->email) }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           title="Edit"
                                           aria-label="Edit"
                                           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.users.destroy', $user) }}"
                                              data-confirm-submit
                                              data-confirm-title="Hapus user?"
                                              data-confirm-text="User {{ $user->email }} akan dihapus jika belum memiliki transaksi atau aktivitas terkait."
                                              data-confirm-button="Ya, hapus"
                                              data-confirm-icon="warning">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Delete"
                                                    aria-label="Delete"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-white text-red-600 transition hover:bg-red-50">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18" />
                                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
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
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 font-semibold text-slate-900">Belum ada user</h3>
                                        <p class="mt-1 text-sm text-slate-500">Tambahkan user baru untuk mulai mengatur akses sistem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($users as $user)
                    @php
                        $roleNames = $user->roles->pluck('name')->values()->all();
                        $roleText = implode(', ', $roleNames) ?: '-';
                        $initials = collect(explode(' ', $user->name))->filter()->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('');
                    @endphp

                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sm font-bold uppercase text-sky-700 ring-1 ring-sky-100">
                                {{ $initials ?: 'U' }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                <div class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</div>

                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @forelse ($user->roles as $role)
                                        <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-500 ring-1 ring-slate-100">
                                            No role
                                        </span>
                                    @endforelse
                                </div>

                                <div class="mt-3 text-xs text-slate-500">
                                    Created: {{ $user->created_at?->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap justify-end gap-2">
                            <button type="button"
                                    title="Detail"
                                    aria-label="Detail"
                                    class="user-detail-button inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                    data-name="{{ e($user->name) }}"
                                    data-email="{{ e($user->email) }}"
                                    data-roles="{{ e($roleText) }}"
                                    data-created="{{ $user->created_at?->format('d M Y H:i') }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>

                            <button type="button"
                                    title="Copy Email"
                                    aria-label="Copy Email"
                                    class="copy-email-button inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700"
                                    data-email="{{ e($user->email) }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                </svg>
                            </button>

                            <a href="{{ route('admin.users.edit', $user) }}"
                               title="Edit"
                               aria-label="Edit"
                               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user) }}"
                                  data-confirm-submit
                                  data-confirm-title="Hapus user?"
                                  data-confirm-text="User {{ $user->email }} akan dihapus jika belum memiliki transaksi atau aktivitas terkait."
                                  data-confirm-button="Ya, hapus"
                                  data-confirm-icon="warning">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        title="Delete"
                                        aria-label="Delete"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-white text-red-600 transition hover:bg-red-50">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada user.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4 sm:p-5">
                {{ $users->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
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

            document.querySelectorAll('.copy-email-button').forEach(function (button) {
                button.addEventListener('click', async function () {
                    const email = button.dataset.email || '';

                    try {
                        await navigator.clipboard.writeText(email);
                        showToast('success', 'Email berhasil disalin');
                    } catch (error) {
                        showToast('error', 'Gagal menyalin email');
                    }
                });
            });

            document.querySelectorAll('.user-detail-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const html = `
                        <div class="space-y-3 text-left text-sm text-slate-700">
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Name</div>
                                <div class="mt-1 font-semibold text-slate-900">${escapeHtml(button.dataset.name || '-')}</div>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</div>
                                <div class="mt-1 font-semibold text-slate-900">${escapeHtml(button.dataset.email || '-')}</div>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</div>
                                <div class="mt-1 font-semibold text-slate-900">${escapeHtml(button.dataset.roles || '-')}</div>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Created</div>
                                <div class="mt-1 font-semibold text-slate-900">${escapeHtml(button.dataset.created || '-')}</div>
                            </div>
                        </div>
                    `;

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'info',
                            title: 'User Detail',
                            html: html,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#0ea5e9'
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>
