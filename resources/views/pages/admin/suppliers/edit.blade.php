<x-layouts.app :title="__('Edit Supplier')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Edit Supplier"
            description="Perbarui informasi supplier, kontak, alamat, dan status aktif."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.suppliers.index') }}" variant="secondary">
                    Back to Suppliers
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.suppliers.update', $supplier) }}"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Update supplier?"
              data-confirm-text="Perubahan data supplier akan disimpan."
              data-confirm-button="Ya, update"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-bold text-slate-900">Supplier Information</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Data utama supplier yang akan digunakan pada proses purchase dan inventory.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">
                                Supplier Name
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $supplier->name) }}"
                                   placeholder="Contoh: PT Sumber Makmur"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100"
                                   autofocus>

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700">
                                Phone
                            </label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $supplier->phone) }}"
                                   placeholder="0812 3456 7890"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $supplier->email) }}"
                                   placeholder="supplier@example.com"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-slate-700">
                                Address
                            </label>
                            <textarea id="address"
                                      name="address"
                                      rows="5"
                                      placeholder="Alamat lengkap supplier"
                                      class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('address', $supplier->address) }}</textarea>

                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Supplier Status</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Supplier nonaktif tidak direkomendasikan untuk transaksi purchase baru.
                            </p>
                        </div>

                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-sky-200 hover:bg-sky-50">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   data-supplier-active
                                   class="rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                   @checked(old('is_active', $supplier->is_active))>

                            <span class="text-sm font-semibold text-slate-700">Active Supplier</span>
                        </label>
                    </div>

                    @error('is_active')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-400 to-cyan-400 text-xl font-black uppercase text-white shadow-sm">
                            {{ strtoupper(substr(old('name', $supplier->name), 0, 2)) }}
                        </div>

                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-bold text-slate-900">
                                {{ old('name', $supplier->name) }}
                            </h2>
                            <p class="mt-1 truncate text-sm text-slate-500">
                                {{ old('email', $supplier->email) ?: 'No email set' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3 rounded-2xl bg-slate-50 p-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Phone</span>
                            <span class="text-right font-semibold text-slate-900">
                                {{ old('phone', $supplier->phone) ?: '-' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Status</span>
                            @if (old('is_active', $supplier->is_active))
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 ring-1 ring-slate-200">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500">Created</span>
                            <span class="text-right font-semibold text-slate-900">
                                {{ $supplier->created_at?->format('d M Y') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Update Action</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pastikan informasi supplier sudah benar sebelum menyimpan perubahan.
                    </p>

                    <div class="mt-5 grid gap-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Update Supplier
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.suppliers.index') }}" variant="secondary" class="w-full">
                            Cancel
                        </x-ui.link-button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Tips</h2>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-500">
                        <li class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                            <span>Gunakan nomor telepon yang aktif untuk koordinasi purchase.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                            <span>Email akan membantu pencatatan kontak supplier.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                            <span>Nonaktifkan supplier jika sudah tidak digunakan.</span>
                        </li>
                    </ul>
                </x-ui.card>
            </aside>
        </form>
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
