<x-layouts.app :title="__('Edit Warehouse')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Edit Warehouse"
            description="Perbarui data lokasi penyimpanan stok."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.warehouses.index') }}" variant="secondary">
                    Back
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <form method="POST" action="{{ route('admin.warehouses.update', $warehouse) }}" class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Update warehouse?"
              data-confirm-text="Perubahan data warehouse akan disimpan."
              data-confirm-button="Ya, update"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900">Warehouse Information</h2>
                        <p class="mt-1 text-sm text-slate-500">Perbarui nama, kode unik, dan alamat warehouse.</p>
                    </div>

                    <div class="mt-5 grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $warehouse->name) }}"
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
                                   value="{{ old('code', $warehouse->code) }}"
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
                                  class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('address', $warehouse->address) }}</textarea>

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
                                   @checked(old('is_default', $warehouse->is_default))>

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
                                   @checked(old('is_active', $warehouse->is_active))>

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
                            <span id="previewCode">{{ Str::limit($warehouse->code, 6, '') }}</span>
                        </div>

                        <div>
                            <div class="text-sm text-slate-500">Warehouse Preview</div>
                            <div id="previewName" class="mt-1 font-bold text-slate-900">{{ $warehouse->name }}</div>
                            <div id="previewStatus" class="mt-1 text-xs font-semibold {{ $warehouse->is_active ? 'text-emerald-700' : 'text-slate-500' }}">
                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 space-y-2 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 ring-1 ring-slate-100">
                        <div class="flex justify-between gap-3">
                            <span>Default</span>
                            <span id="previewDefault" class="font-semibold text-slate-900">{{ $warehouse->is_default ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span>Code</span>
                            <span class="font-mono font-semibold text-slate-900">{{ $warehouse->code }}</span>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Update Warehouse
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
            const previewDefault = document.getElementById('previewDefault');

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
                previewName.textContent = nameInput.value || 'Warehouse';
                previewStatus.textContent = activeCheckbox.checked ? 'Active' : 'Inactive';
                previewStatus.className = activeCheckbox.checked
                    ? 'mt-1 text-xs font-semibold text-emerald-700'
                    : 'mt-1 text-xs font-semibold text-slate-500';
                previewDefault.textContent = defaultCheckbox.checked ? 'Yes' : 'No';
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
                    fireToast(
                        defaultCheckbox.checked ? 'info' : 'warning',
                        defaultCheckbox.checked ? 'Warehouse ini akan dijadikan default.' : 'Warehouse ini tidak lagi ditandai default.'
                    );
                    syncPreview();
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
