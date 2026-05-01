<x-layouts.app :title="__('Create Category')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Add Category"
            description="Tambahkan kategori produk baru untuk mengelompokkan item di katalog dan POS."
        >
            <x-slot:actions>
                <x-ui.link-button href="{{ route('admin.categories.index') }}" variant="secondary">
                    Back
                </x-ui.link-button>
            </x-slot:actions>
        </x-page-header>

        <form method="POST"
              action="{{ route('admin.categories.store') }}"
              data-confirm-submit
              data-confirm-title="Simpan category?"
              data-confirm-text="Category baru akan ditambahkan ke master data."
              data-confirm-button="Ya, simpan"
              data-confirm-icon="question">
            @csrf

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
                <x-ui.card>
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Category Information</h2>
                            <p class="mt-1 text-sm text-slate-500">Isi nama dan deskripsi category.</p>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100"
                                   autofocus>

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="5"
                                      class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-100">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <div class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                    <x-ui.card>
                        <div class="space-y-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Category Status</h2>
                                <p class="mt-1 text-sm text-slate-500">Category aktif akan tersedia untuk produk.</p>
                            </div>

                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="isActive"
                                       class="mt-1 rounded border-slate-300 text-sky-500 focus:ring-sky-100"
                                       @checked(old('is_active', true))>

                                <span>
                                    <span class="block text-sm font-semibold text-slate-800">Active</span>
                                    <span class="mt-1 block text-xs leading-5 text-slate-500">Nonaktifkan jika category belum ingin dipakai.</span>
                                </span>
                            </label>

                            @error('is_active')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="space-y-3">
                            <x-ui.button-primary type="submit" class="w-full">
                                Save Category
                            </x-ui.button-primary>

                            <x-ui.link-button href="{{ route('admin.categories.index') }}" variant="secondary" class="w-full">
                                Cancel
                            </x-ui.link-button>
                        </div>
                    </x-ui.card>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isActive = document.getElementById('isActive');

            if (isActive) {
                isActive.addEventListener('change', function () {
                    if (!window.Toast) return;

                    Toast.fire({
                        icon: this.checked ? 'success' : 'info',
                        title: this.checked ? 'Category akan aktif.' : 'Category akan disimpan nonaktif.'
                    });
                });
            }
        });
    </script>
</x-layouts.app>
