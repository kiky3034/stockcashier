@props([
    'href',
    'active' => false,
])

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => $active
            ? 'flex items-center rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white'
            : 'flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900'
   ]) }}>
    {{ $slot }}
</a>