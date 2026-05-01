<x-layouts.app :title="__('Edit Unit')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Edit Unit"
            description="Perbarui satuan produk yang digunakan pada data inventory."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.units.index') }}" variant="secondary">
                    Back to Units
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.units.update', $unit) }}"
              class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]"
              data-confirm-submit
              data-confirm-title="Update unit?"
              data-confirm-text="Perubahan pada unit {{ $unit->name }} akan disimpan."
              data-confirm-button="Ya, update unit"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <x-ui.card>
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-lg font-bold text-slate-900">Unit Information</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Isi nama satuan dan singkatan yang akan tampil pada produk, stok, dan transaksi.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700">
                                Unit Name
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $unit->name) }}"
                                   placeholder="Contoh: Kilogram"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100"
                                   autofocus>

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="abbreviation" class="block text-sm font-semibold text-slate-700">
                                Abbreviation
                            </label>
                            <input type="text"
                                   id="abbreviation"
                                   name="abbreviation"
                                   value="{{ old('abbreviation', $unit->abbreviation) }}"
                                   placeholder="Contoh: KG"
                                   class="py-2 mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 font-mono text-sm uppercase tracking-wide text-slate-800 shadow-sm transition placeholder:font-sans placeholder:normal-case placeholder:tracking-normal placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                            <p class="mt-2 text-xs text-slate-500">
                                Singkatan akan otomatis dibuat huruf besar saat diketik.
                            </p>

                            @error('abbreviation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Status
                            </label>

                            <label class="mt-2 flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                                <input type="checkbox"
                                       id="unitActiveToggle"
                                       name="is_active"
                                       value="1"
                                       class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                       @checked(old('is_active', $unit->is_active))>

                                <span>
                                    <span class="block text-sm font-semibold text-slate-800">Active Unit</span>
                                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                                        Unit aktif bisa dipilih saat membuat atau mengedit produk.
                                    </span>
                                </span>
                            </label>

                            @error('is_active')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div class="flex gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-900">Catatan perubahan unit</h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Perubahan nama atau singkatan unit akan memengaruhi tampilan unit pada produk, stok,
                                dan riwayat transaksi yang menampilkan data unit dari relasi produk.
                            </p>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                <x-ui.card>
                    <div class="text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-sky-500 to-cyan-400 text-2xl font-black uppercase text-white shadow-sm shadow-sky-500/20" id="unitPreviewAbbreviation">
                            {{ strtoupper(substr(old('abbreviation', $unit->abbreviation), 0, 4)) }}
                        </div>

                        <h2 class="mt-4 text-lg font-bold text-slate-900" id="unitPreviewName">
                            {{ old('name', $unit->name) }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Unit ID: #{{ $unit->id }}
                        </p>
                    </div>

                    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Current Status</span>
                            <span id="unitPreviewStatus"
                                  class="rounded-full px-2.5 py-1 text-xs font-semibold {{ old('is_active', $unit->is_active) ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'bg-slate-100 text-slate-600 ring-1 ring-slate-200' }}">
                                {{ old('is_active', $unit->is_active) ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-3">
                        <x-ui.button-primary type="submit" class="w-full">
                            Update Unit
                        </x-ui.button-primary>

                        <x-ui.link-button href="{{ route('admin.units.index') }}" variant="secondary" class="w-full">
                            Cancel
                        </x-ui.link-button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h2 class="font-bold text-slate-900">Quick Tips</h2>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-500">
                        <li class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-sky-400"></span>
                            Gunakan abbreviation singkat seperti PCS, KG, L, BOX.
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-sky-400"></span>
                            Nonaktifkan unit jika tidak ingin dipakai untuk produk baru.
                        </li>
                    </ul>
                </x-ui.card>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const abbreviationInput = document.getElementById('abbreviation');
            const activeToggle = document.getElementById('unitActiveToggle');
            const previewName = document.getElementById('unitPreviewName');
            const previewAbbreviation = document.getElementById('unitPreviewAbbreviation');
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

            function updatePreview() {
                if (previewName && nameInput) {
                    previewName.textContent = nameInput.value || 'Unit Name';
                }

                if (previewAbbreviation && abbreviationInput) {
                    previewAbbreviation.textContent = (abbreviationInput.value || 'U').slice(0, 4).toUpperCase();
                }

                if (previewStatus && activeToggle) {
                    previewStatus.textContent = activeToggle.checked ? 'Active' : 'Inactive';
                    previewStatus.className = activeToggle.checked
                        ? 'rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100'
                        : 'rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200';
                }
            }

            if (abbreviationInput) {
                abbreviationInput.addEventListener('input', function () {
                    this.value = this.value.toUpperCase();
                    updatePreview();
                });
            }

            if (nameInput) {
                nameInput.addEventListener('input', updatePreview);
            }

            if (activeToggle) {
                activeToggle.addEventListener('change', function () {
                    updatePreview();
                    showToast(
                        this.checked ? 'info' : 'warning',
                        this.checked ? 'Unit akan aktif setelah diupdate.' : 'Unit akan nonaktif setelah diupdate.'
                    );
                });
            }

            updatePreview();
        });
    </script>
</x-layouts.app>
