<x-layouts.app :title="__('Create Supplier')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Add Supplier"
            description="Tambahkan supplier baru ke master data StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.suppliers.index') }}" variant="secondary">
                    Back to Suppliers
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.suppliers.store') }}"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Simpan supplier baru?"
              data-confirm-text="Supplier baru akan ditambahkan ke master data."
              data-confirm-button="Ya, simpan"
              data-confirm-icon="question">
            @csrf

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-bold text-slate-900">Supplier Information</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Isi identitas dan kontak supplier. Data ini akan dipakai saat membuat purchase.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">
                                Supplier Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: PT Sumber Makmur"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100"
                                   autofocus>

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700">
                                Phone
                            </label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Contoh: supplier@example.com"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-slate-700">
                                Address
                            </label>
                            <textarea id="address"
                                      name="address"
                                      rows="5"
                                      placeholder="Alamat supplier"
                                      class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('address') }}</textarea>

                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Supplier Status</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Supplier aktif bisa dipakai pada transaksi pembelian.
                            </p>
                        </div>

                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:bg-sky-50">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   data-supplier-active
                                   class="rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                   @checked(old('is_active', true))>

                            <span class="text-sm font-semibold text-slate-700">Active Supplier</span>
                        </label>
                    </div>

                    @error('is_active')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="text-center">
                        <div id="supplierInitials"
                             class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-500 to-cyan-400 text-2xl font-black text-white shadow-sm shadow-sky-500/20">
                            SP
                        </div>

                        <h2 id="supplierPreviewName" class="mt-4 text-lg font-bold text-slate-900">
                            New Supplier
                        </h2>

                        <p id="supplierPreviewEmail" class="mt-1 text-sm text-slate-500">
                            Email belum diisi
                        </p>

                        <div id="supplierPreviewPhone"
                             class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            Phone belum diisi
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl bg-sky-50 p-4 text-sm text-sky-800 ring-1 ring-sky-100">
                        <div class="font-semibold text-sky-900">Tips</div>
                        <p class="mt-1 text-xs leading-5">
                            Gunakan nama supplier resmi agar laporan purchase lebih mudah dibaca.
                        </p>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Save Supplier
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.suppliers.index') }}" variant="secondary" class="w-full">
                            Cancel
                        </x-ui.link-button>
                    </div>
                </x-ui.card>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activeInput = document.querySelector('[data-supplier-active]');
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');
            const supplierInitials = document.getElementById('supplierInitials');
            const supplierPreviewName = document.getElementById('supplierPreviewName');
            const supplierPreviewEmail = document.getElementById('supplierPreviewEmail');
            const supplierPreviewPhone = document.getElementById('supplierPreviewPhone');

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
                        timer: 1800,
                        showConfirmButton: false,
                    });
                }
            }

            function getInitials(value) {
                const words = String(value || '')
                    .trim()
                    .split(/\s+/)
                    .filter(Boolean)
                    .slice(0, 2);

                if (words.length === 0) {
                    return 'SP';
                }

                return words.map(word => word.charAt(0).toUpperCase()).join('');
            }

            function updatePreview() {
                const name = nameInput?.value?.trim() || 'New Supplier';
                const email = emailInput?.value?.trim() || 'Email belum diisi';
                const phone = phoneInput?.value?.trim() || 'Phone belum diisi';

                supplierInitials.textContent = getInitials(name);
                supplierPreviewName.textContent = name;
                supplierPreviewEmail.textContent = email;
                supplierPreviewPhone.textContent = phone;
            }

            activeInput?.addEventListener('change', function () {
                showToast(
                    this.checked ? 'info' : 'warning',
                    this.checked ? 'Supplier akan dibuat aktif.' : 'Supplier akan dibuat nonaktif.'
                );
            });

            phoneInput?.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
                updatePreview();
            });

            emailInput?.addEventListener('input', updatePreview);

            emailInput?.addEventListener('blur', function () {
                if (this.value && !this.checkValidity()) {
                    showToast('warning', 'Format email supplier belum valid.');
                }
            });

            nameInput?.addEventListener('input', updatePreview);

            updatePreview();
        });
    </script>
</x-layouts.app>
