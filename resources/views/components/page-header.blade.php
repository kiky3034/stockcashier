{{-- page-header.blade.php tanpa judul --}}
@props(['title' => null, 'description' => null])

{{-- Hanya tampilkan actions slot jika ada --}}
@if (isset($actions))
    <div class="mb-6 flex items-center justify-end gap-2">
        {{ $actions }}
    </div>
@endif

{{-- Flash Messages --}}
@php
    $flashMessages = [];

    if (session('success')) {
        $flashMessages[] = [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => session('success'),
            'classes' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'iconClasses' => 'bg-emerald-100 text-emerald-700',
            'buttonClasses' => 'text-emerald-700 hover:bg-emerald-100',
            'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ];
    }

    if (session('error')) {
        $flashMessages[] = [
            'type' => 'error',
            'title' => 'Gagal',
            'message' => session('error'),
            'classes' => 'border-red-200 bg-red-50 text-red-800',
            'iconClasses' => 'bg-red-100 text-red-700',
            'buttonClasses' => 'text-red-700 hover:bg-red-100',
            'icon' => 'M9.75 9.75 14.25 14.25M14.25 9.75 9.75 14.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ];
    }

    if (session('warning')) {
        $flashMessages[] = [
            'type' => 'warning',
            'title' => 'Perhatian',
            'message' => session('warning'),
            'classes' => 'border-amber-200 bg-amber-50 text-amber-800',
            'iconClasses' => 'bg-amber-100 text-amber-700',
            'buttonClasses' => 'text-amber-700 hover:bg-amber-100',
            'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z',
        ];
    }

    if (session('info')) {
        $flashMessages[] = [
            'type' => 'info',
            'title' => 'Informasi',
            'message' => session('info'),
            'classes' => 'border-sky-200 bg-sky-50 text-sky-800',
            'iconClasses' => 'bg-sky-100 text-sky-700',
            'buttonClasses' => 'text-sky-700 hover:bg-sky-100',
            'icon' => 'm11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z',
        ];
    }

    if ($errors->any()) {
        $flashMessages[] = [
            'type' => 'validation',
            'title' => 'Validasi gagal',
            'message' => $errors->first(),
            'classes' => 'border-red-200 bg-red-50 text-red-800',
            'iconClasses' => 'bg-red-100 text-red-700',
            'buttonClasses' => 'text-red-700 hover:bg-red-100',
            'icon' => 'M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.29 3.86 1.82 18a1.5 1.5 0 0 0 1.29 2.25h17.78A1.5 1.5 0 0 0 22.18 18L13.71 3.86a1.5 1.5 0 0 0-2.42 0Z',
        ];
    }
@endphp

@if (count($flashMessages) > 0)
    <div class="space-y-3 mb-6">
        @foreach ($flashMessages as $flash)
            <div class="group flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-sm {{ $flash['classes'] }}"
                 data-dismissible-alert>
                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $flash['iconClasses'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $flash['icon'] }}" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-sm font-bold">
                        {{ $flash['title'] }}
                    </div>

                    <div class="mt-0.5 text-sm leading-6 opacity-90">
                        {{ $flash['message'] }}
                    </div>
                </div>

                <button type="button"
                        class="rounded-lg p-1.5 opacity-70 transition hover:opacity-100 {{ $flash['buttonClasses'] }}"
                        aria-label="Tutup pesan"
                        onclick="this.closest('[data-dismissible-alert]').remove()">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.8"
                         stroke="currentColor"
                         class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif