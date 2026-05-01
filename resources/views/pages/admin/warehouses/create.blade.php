<x-layouts.app :title="__('Create Warehouse')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Add Warehouse"
            description="Tambahkan lokasi penyimpanan stok baru untuk StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.warehouses.index') }}" variant="secondary">
                    Back
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <form method="POST" action="{{ route('admin.warehouses.store') }}" class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Simpan warehouse baru?"
              data-confirm-text="Warehouse akan ditambahkan sebagai lokasi stok baru."
              data-confirm-button="Ya, simpan"
              data-confirm-icon="question">
            @csrf

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900">Warehouse Information</h2>
                        <p class="mt-1 text-sm text-slate-500">Isi nama, kode unik, dan alamat warehouse.</p>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Main Warehouse"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100"
                                   autofocus>

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700">Code</label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   autocomplete="off"
                                   value="{{ old('code') }}"
                                   placeholder="Contoh: MAIN"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 font-mono text-sm uppercase shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            <p class="mt-1 text-xs text-slate-500">Kode otomatis uppercase dan spasi diganti tanda minus.</p>

                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <label for="address" class="block text-sm font-semibold text-slate-700">Address</label>
                        <textarea id="address"
                                  name="address"
                                  rows="5"
                                  placeholder="Alamat warehouse"
                                  class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('address') }}</textarea>

                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900">Warehouse Status</h2>
                        <p class="mt-1 text-sm text-slate-500">Atur status operasional dan default warehouse.</p>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                            <input type="checkbox"
                                   name="is_default"
                                   id="is_default"
                                   value="1"
                                   class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                   @checked(old('is_default', false))>

                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Set as default warehouse</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">Warehouse ini akan menjadi pilihan utama untuk stok/transaksi.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                   @checked(old('is_active', true))>

                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Active</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">Warehouse aktif dapat digunakan untuk stock dan purchase.</span>
                            </span>
                        </label>
                    </div>

                    @error('is_default')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('is_active')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-500 to-cyan-400 font-mono text-xl font-black text-white shadow-sm">
                            <span id="previewCode">WH</span>
                        </div>

                        <div>
                            <div class="text-sm text-slate-500">Preview</div>
                            <div id="previewName" class="mt-1 font-bold text-slate-900">New Warehouse</div>
                            <div id="previewStatus" class="mt-1 text-xs font-semibold text-emerald-700">Active</div>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl bg-sky-50 p-4 text-sm text-sky-800 ring-1 ring-sky-100">
                        Gunakan kode pendek yang mudah dikenali, seperti <span class="font-bold">MAIN</span>, <span class="font-bold">STORE-01</span>, atau <span class="font-bold">GUDANG-A</span>.
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Save Warehouse
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.warehouses.index') }}" variant="secondary" class="w-full">
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
            const codeInput = document.getElementById('code');
            const defaultCheckbox = document.getElementById('is_default');
            const activeCheckbox = document.getElementById('is_active');
            const previewCode = document.getElementById('previewCode');
            const previewName = document.getElementById('previewName');
            const previewStatus = document.getElementById('previewStatus');

            function fireToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            function syncPreview() {
                previewCode.textContent = (codeInput.value || 'WH').slice(0, 6);
                previewName.textContent = nameInput.value || 'New Warehouse';
                previewStatus.textContent = activeCheckbox.checked ? 'Active' : 'Inactive';
                previewStatus.className = activeCheckbox.checked
                    ? 'mt-1 text-xs font-semibold text-emerald-700'
                    : 'mt-1 text-xs font-semibold text-slate-500';
            }

            if (codeInput) {
                codeInput.addEventListener('input', function () {
                    codeInput.value = codeInput.value.toUpperCase().replace(/\s+/g, '-');
                    syncPreview();
                });
            }

            if (nameInput) {
                nameInput.addEventListener('input', syncPreview);
            }

            if (defaultCheckbox) {
                defaultCheckbox.addEventListener('change', function () {
                    if (defaultCheckbox.checked) {
                        fireToast('info', 'Warehouse ini akan dijadikan default.');
                    }
                });
            }

            if (activeCheckbox) {
                activeCheckbox.addEventListener('change', function () {
                    fireToast(
                        activeCheckbox.checked ? 'success' : 'warning',
                        activeCheckbox.checked ? 'Warehouse akan aktif.' : 'Warehouse akan nonaktif.'
                    );
                    syncPreview();
                });
            }

            syncPreview();
        });
    </script>
</x-layouts.app>
