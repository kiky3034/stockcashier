@props([
    'type' => 'button',
])

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100 disabled:cursor-not-allowed disabled:opacity-60'
    ]) }}>
    {{ $slot }}
</button>