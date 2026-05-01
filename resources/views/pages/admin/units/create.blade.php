<x-layouts.app :title="__('Create Unit')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Add Unit"
            description="Tambahkan satuan produk baru untuk digunakan pada master product dan transaksi stok."
        >
            <x-slot:actions>
                <a href="{{ route('admin.units.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    Back to Units
                </a>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.units.store') }}"
              data-confirm-submit
              data-confirm-title="Simpan unit baru?"
              data-confirm-text="Unit baru akan ditambahkan ke master data satuan produk."
              data-confirm-button="Ya, simpan unit"
              data-confirm-icon="question">
            @csrf

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <x-ui.card>
                        <div class="border-b border-slate-100 pb-5">
                            <h2 class="text-lg font-bold text-slate-900">Unit Information</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Isi nama satuan dan singkatan yang akan tampil di produk, stok, purchase, dan POS.
                            </p>
                        </div>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700">
                                    Name
                                </label>

                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Contoh: Piece"
                                       class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100"
                                       autofocus>

                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="abbreviation" class="block text-sm font-semibold text-slate-700">
                                    Abbreviation
                                </label>

                                <input type="text"
                                       id="abbreviation"
                                       name="abbreviation"
                                       value="{{ old('abbreviation') }}"
                                       placeholder="Contoh: PCS"
                                       class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 font-mono text-sm uppercase tracking-wide text-slate-800 shadow-sm transition placeholder:font-sans placeholder:normal-case placeholder:tracking-normal placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                                <p class="mt-1 text-xs text-slate-500">
                                    Singkatan akan otomatis dibuat uppercase saat diketik.
                                </p>

                                @error('abbreviation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700">
                                    Status
                                </label>

                                <label class="mt-2 flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50/70">
                                    <input type="checkbox"
                                           id="unitActiveToggle"
                                           name="is_active"
                                           value="1"
                                           class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                           @checked(old('is_active', true))>

                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800">Active Unit</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">
                                            Unit aktif bisa dipilih saat membuat atau mengedit produk.
                                        </span>
                                    </span>
                                </label>

                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16" />
                                    <path d="M4 12h16" />
                                    <path d="M4 17h10" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="font-bold text-slate-900">Tips Penamaan Unit</h2>
                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    Gunakan nama yang jelas seperti Piece, Box, Kilogram, Liter, atau Pack.
                                    Untuk abbreviation, gunakan bentuk singkat seperti PCS, BOX, KG, L, atau PCK.
                                </p>
                            </div>
                        </div>
                    </x-ui.card>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                    <x-ui.card>
                        <div class="text-center">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-100 to-cyan-100 text-2xl font-black uppercase tracking-wide text-sky-700 ring-1 ring-sky-100" id="unitPreviewAbbr">
                                {{ old('abbreviation') ?: 'UNT' }}
                            </div>

                            <h2 class="mt-4 text-lg font-bold text-slate-900" id="unitPreviewName">
                                {{ old('name') ?: 'Unit Name' }}
                            </h2>

                            <div class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ old('is_active', true) ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-slate-50 text-slate-600 ring-slate-200' }}" id="unitPreviewStatus">
                                {{ old('is_active', true) ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="space-y-3">
                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                Save Unit
                            </button>

                            <a href="{{ route('admin.units.index') }}"
                               class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                                Cancel
                            </a>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <h3 class="font-bold text-slate-900">Access Notes</h3>
                        <ul class="mt-3 space-y-2 text-sm text-slate-500">
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                Unit aktif dapat digunakan di form produk.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                Unit nonaktif tetap tersimpan untuk data lama.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                Abbreviation membantu tampilan POS dan inventory lebih ringkas.
                            </li>
                        </ul>
                    </x-ui.card>
                </aside>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const abbreviationInput = document.getElementById('abbreviation');
            const activeToggle = document.getElementById('unitActiveToggle');
            const previewName = document.getElementById('unitPreviewName');
            const previewAbbr = document.getElementById('unitPreviewAbbr');
            const previewStatus = document.getElementById('unitPreviewStatus');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            function syncPreview() {
                if (previewName) {
                    previewName.textContent = nameInput?.value?.trim() || 'Unit Name';
                }

                if (previewAbbr) {
                    previewAbbr.textContent = abbreviationInput?.value?.trim() || 'UNT';
                }

                if (previewStatus && activeToggle) {
                    previewStatus.textContent = activeToggle.checked ? 'Active' : 'Inactive';
                    previewStatus.className = activeToggle.checked
                        ? 'mt-2 inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100'
                        : 'mt-2 inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200';
                }
            }

            if (abbreviationInput) {
                abbreviationInput.addEventListener('input', function () {
                    this.value = this.value.toUpperCase();
                    syncPreview();
                });
            }

            if (nameInput) {
                nameInput.addEventListener('input', syncPreview);
            }

            if (activeToggle) {
                activeToggle.addEventListener('change', function () {
                    syncPreview();
                    showToast(
                        this.checked ? 'info' : 'warning',
                        this.checked ? 'Unit akan aktif setelah disimpan.' : 'Unit akan nonaktif setelah disimpan.'
                    );
                });
            }

            syncPreview();
        });
    </script>
</x-layouts.app>
