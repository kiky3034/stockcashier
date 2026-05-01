<x-layouts.app :title="__('Users')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Kelola user dan role StockCashier.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                + Add User
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari user..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Search
                    </button>

                    @if ($search)
                        <a href="{{ route('admin.users.index') }}"
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Role</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Created</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($users as $user)
                            @php
                                $roleNames = $user->roles->pluck('name')->values()->all();
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="mt-1 text-xs text-gray-500">{{ $user->email }}</div>
                                </td>

                                <td class="px-4 py-3">
                                    @forelse ($user->roles as $role)
                                        <span class="mr-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-500">No role</span>
                                    @endforelse
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $user->created_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <button type="button"
                                                class="user-detail-button rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-name="{{ e($user->name) }}"
                                                data-email="{{ e($user->email) }}"
                                                data-roles="{{ e(implode(', ', $roleNames) ?: '-') }}"
                                                data-created="{{ $user->created_at?->format('d M Y H:i') }}">
                                            Detail
                                        </button>

                                        <button type="button"
                                                class="copy-email-button rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                                data-email="{{ e($user->email) }}">
                                            Copy Email
                                        </button>

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            Edit
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
                                    Belum ada user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $users->links() }}
            </div>
        </div>
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
                        <div class="text-left text-sm">
                            <div class="mb-2"><strong>Name:</strong> ${button.dataset.name || '-'}</div>
                            <div class="mb-2"><strong>Email:</strong> ${button.dataset.email || '-'}</div>
                            <div class="mb-2"><strong>Role:</strong> ${button.dataset.roles || '-'}</div>
                            <div><strong>Created:</strong> ${button.dataset.created || '-'}</div>
                        </div>
                    `;

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'info',
                            title: 'User Detail',
                            html: html,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#111827'
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>
