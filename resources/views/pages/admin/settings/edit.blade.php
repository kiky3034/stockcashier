<x-layouts.app :title="__('Settings')">
    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Settings"
            description="Atur profil toko, branding receipt, dan perilaku print StockCashier."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.dashboard') }}" variant="secondary">
                    Back to Dashboard
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <x-flash-message />

        <form method="POST"
              action="{{ route('admin.settings.update') }}"
              enctype="multipart/form-data"
              id="settingsForm"
              class="space-y-6"
              data-confirm-submit
              data-confirm-title="Simpan settings?"
              data-confirm-text="Profil toko dan pengaturan struk akan diperbarui."
              data-confirm-button="Ya, simpan settings"
              data-confirm-icon="question">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <x-ui.card>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Store Profile</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Informasi toko yang tampil di sistem dan receipt.
                                </p>
                            </div>

                            <div class="rounded-2xl bg-sky-50 p-3 text-sky-600 ring-1 ring-sky-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <path d="M9 22V12h6v10" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 space-y-5">
                            <div>
                                <label for="store_name" class="block text-sm font-semibold text-slate-700">
                                    Store Name
                                </label>

                                <input type="text"
                                       id="store_name"
                                       name="store_name"
                                       value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                                       class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                                @error('store_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_address" class="block text-sm font-semibold text-slate-700">
                                    Store Address
                                </label>

                                <textarea id="store_address"
                                          name="store_address"
                                          rows="4"
                                          class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>

                                @error('store_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label for="store_phone" class="block text-sm font-semibold text-slate-700">
                                        Store Phone
                                    </label>

                                    <input type="text"
                                           id="store_phone"
                                           name="store_phone"
                                           value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                                           class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                                    @error('store_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="store_email" class="block text-sm font-semibold text-slate-700">
                                        Store Email
                                    </label>

                                    <input type="email"
                                           id="store_email"
                                           name="store_email"
                                           value="{{ old('store_email', $settings['store_email'] ?? '') }}"
                                           class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                                    @error('store_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Receipt Content</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Atur mata uang dan pesan footer pada receipt.
                                </p>
                            </div>

                            <div class="rounded-2xl bg-cyan-50 p-3 text-cyan-600 ring-1 ring-cyan-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                                    <path d="M8 8h8" />
                                    <path d="M8 12h8" />
                                    <path d="M8 16h5" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 space-y-5">
                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label for="currency_prefix" class="block text-sm font-semibold text-slate-700">
                                        Currency Prefix
                                    </label>

                                    <input type="text"
                                           id="currency_prefix"
                                           name="currency_prefix"
                                           value="{{ old('currency_prefix', $settings['currency_prefix'] ?? 'Rp') }}"
                                           class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">

                                    @error('currency_prefix')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="receipt_paper_size" class="block text-sm font-semibold text-slate-700">
                                        Receipt Paper Size
                                    </label>

                                    <select id="receipt_paper_size"
                                            name="receipt_paper_size"
                                            class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                                        <option value="80mm" @selected(old('receipt_paper_size', $settings['receipt_paper_size'] ?? '80mm') === '80mm')>
                                            80mm Thermal Paper
                                        </option>
                                        <option value="58mm" @selected(old('receipt_paper_size', $settings['receipt_paper_size'] ?? '80mm') === '58mm')>
                                            58mm Thermal Paper
                                        </option>
                                    </select>

                                    @error('receipt_paper_size')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="receipt_footer" class="block text-sm font-semibold text-slate-700">
                                    Receipt Footer
                                </label>

                                <textarea id="receipt_footer"
                                          name="receipt_footer"
                                          rows="4"
                                          class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>

                                @error('receipt_footer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                                    <input type="checkbox"
                                           name="receipt_auto_print"
                                           value="1"
                                           class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                           @checked(old('receipt_auto_print', ($settings['receipt_auto_print'] ?? 'false') === 'true'))>

                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800">Auto print receipt</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">Browser akan membuka dialog print saat receipt dibuka.</span>
                                    </span>
                                </label>

                                <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                                    <input type="checkbox"
                                           name="receipt_show_logo"
                                           value="1"
                                           class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                           @checked(old('receipt_show_logo', ($settings['receipt_show_logo'] ?? 'true') === 'true'))>

                                    <span>
                                        <span class="block text-sm font-semibold text-slate-800">Show logo on receipt</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">Logo toko akan tampil di bagian atas receipt.</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </x-ui.card>
                </div>

                <aside class="space-y-6 xl:sticky xl:top-24 xl:self-start">
                    <x-ui.card>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Store Logo</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Logo akan dipakai di receipt dan branding toko.
                            </p>
                        </div>

                        <div class="mt-5 overflow-hidden rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center">
                            @if (! empty($settings['store_logo']))
                                <img id="logoPreview"
                                     src="{{ asset('storage/' . $settings['store_logo']) }}"
                                     alt="Store Logo"
                                     class="mx-auto h-32 w-32 rounded-2xl border border-slate-200 bg-white object-contain p-3 shadow-sm">
                            @else
                                <div id="logoPlaceholder" class="mx-auto flex h-32 w-32 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm">
                                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="3" rx="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                    </svg>
                                </div>
                                <img id="logoPreview"
                                     src=""
                                     alt="Store Logo Preview"
                                     class="mx-auto hidden h-32 w-32 rounded-2xl border border-slate-200 bg-white object-contain p-3 shadow-sm">
                            @endif
                        </div>

                        <div class="mt-5">
                            <label for="store_logo" class="block text-sm font-semibold text-slate-700">
                                Upload Logo
                            </label>

                            <input type="file"
                                   id="store_logo"
                                   name="store_logo"
                                   accept="image/*"
                                   class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-3 file:rounded-xl file:border-0 file:bg-sky-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-sky-700 hover:file:bg-sky-100">

                            <p class="mt-2 text-xs leading-5 text-slate-500">
                                Format: JPG, PNG, WEBP. Maksimal 2MB.
                            </p>

                            @error('store_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <h2 class="text-lg font-bold text-slate-900">Preview Settings</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Ringkasan cepat pengaturan yang sedang aktif.
                        </p>

                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Store</dt>
                                <dd class="truncate font-semibold text-slate-900">{{ $settings['store_name'] ?? 'StockCashier Store' }}</dd>
                            </div>

                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Paper</dt>
                                <dd class="font-semibold text-slate-900">{{ $settings['receipt_paper_size'] ?? '80mm' }}</dd>
                            </div>

                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Currency</dt>
                                <dd class="font-semibold text-slate-900">{{ $settings['currency_prefix'] ?? 'Rp' }}</dd>
                            </div>
                        </dl>
                    </x-ui.card>

                    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Save Settings
                        </button>

                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            Cancel
                        </a>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoInput = document.getElementById('store_logo');
            const logoPreview = document.getElementById('logoPreview');
            const logoPlaceholder = document.getElementById('logoPlaceholder');
            const storePhoneInput = document.getElementById('store_phone');
            const currencyInput = document.getElementById('currency_prefix');

            function showToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 2200, showConfirmButton: false });
                }
            }

            if (logoInput) {
                logoInput.addEventListener('change', function () {
                    const file = logoInput.files?.[0];

                    if (!file) {
                        return;
                    }

                    const sizeInMb = file.size / 1024 / 1024;

                    if (sizeInMb > 2) {
                        showToast('warning', 'Ukuran logo lebih dari 2MB. Upload mungkin ditolak.');
                    } else {
                        showToast('info', `Logo dipilih: ${file.name}`);
                    }

                    if (logoPreview) {
                        logoPreview.src = URL.createObjectURL(file);
                        logoPreview.classList.remove('hidden');
                    }

                    if (logoPlaceholder) {
                        logoPlaceholder.classList.add('hidden');
                    }
                });
            }

            if (storePhoneInput) {
                storePhoneInput.addEventListener('input', function () {
                    storePhoneInput.value = storePhoneInput.value.replace(/[^0-9+\-()\s]/g, '');
                });
            }

            if (currencyInput) {
                currencyInput.addEventListener('input', function () {
                    currencyInput.value = currencyInput.value.slice(0, 10);
                });
            }
        });
    </script>
</x-layouts.app>
