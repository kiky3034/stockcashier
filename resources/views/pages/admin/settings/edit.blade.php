<x-layouts.app :title="__('Settings')">
    <div class="p-6">
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Atur profil toko dan informasi struk.
                </p>
            </div>

            <x-flash-message />

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700">
                            Store Name
                        </label>

                        <input type="text"
                               id="store_name"
                               name="store_name"
                               value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('store_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="store_address" class="block text-sm font-medium text-gray-700">
                            Store Address
                        </label>

                        <textarea id="store_address"
                                  name="store_address"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>

                        @error('store_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="store_phone" class="block text-sm font-medium text-gray-700">
                                Store Phone
                            </label>

                            <input type="text"
                                   id="store_phone"
                                   name="store_phone"
                                   value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                            @error('store_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="store_email" class="block text-sm font-medium text-gray-700">
                                Store Email
                            </label>

                            <input type="email"
                                   id="store_email"
                                   name="store_email"
                                   value="{{ old('store_email', $settings['store_email'] ?? '') }}"
                                   class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                            @error('store_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="store_logo" class="block text-sm font-medium text-gray-700">
                                Store Logo
                            </label>

                            @if (! empty($settings['store_logo']))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings['store_logo']) }}"
                                        alt="Store Logo"
                                        class="h-20 w-20 rounded-lg border border-gray-200 object-contain">
                                </div>
                            @endif

                            <input type="file"
                                id="store_logo"
                                name="store_logo"
                                accept="image/*"
                                class="mt-2 block w-full text-sm text-gray-700">

                            <p class="mt-1 text-xs text-gray-500">
                                Format: JPG, PNG, WEBP. Maksimal 2MB.
                            </p>

                            @error('store_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="currency_prefix" class="block text-sm font-medium text-gray-700">
                            Currency Prefix
                        </label>

                        <input type="text"
                               id="currency_prefix"
                               name="currency_prefix"
                               value="{{ old('currency_prefix', $settings['currency_prefix'] ?? 'Rp') }}"
                               class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                        @error('currency_prefix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="receipt_footer" class="block text-sm font-medium text-gray-700">
                            Receipt Footer
                        </label>

                        <textarea id="receipt_footer"
                                  name="receipt_footer"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>

                        @error('receipt_footer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>