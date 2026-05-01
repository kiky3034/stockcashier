@props([
    'href',
    'variant' => 'primary',
])

@php
    $classes = [
        'primary' => 'bg-sky-500 text-white shadow-sm hover:bg-sky-600 focus:ring-sky-100',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 shadow-sm hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:ring-sky-100',
        'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-700 focus:ring-red-100',
        'ghost' => 'text-slate-600 hover:bg-sky-50 hover:text-sky-700 focus:ring-sky-100',
    ];

    $class = $classes[$variant] ?? $classes['primary'];
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => 'inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-4 ' . $class
   ]) }}>
    {{ $slot }}
</a>