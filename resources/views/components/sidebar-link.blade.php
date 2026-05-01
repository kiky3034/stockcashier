@props([
    'href',
    'active' => false,
])

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => $active
            ? 'group flex items-center gap-3 rounded-2xl bg-gradient-to-r from-sky-500 to-cyan-500 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-sky-200 transition duration-200 hover:from-sky-600 hover:to-cyan-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400 focus-visible:ring-offset-2'
            : 'group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-600 transition duration-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400 focus-visible:ring-offset-2'
   ]) }}>
    <span class="flex min-w-0 flex-1 items-center gap-3">
        <span class="truncate">
            {{ $slot }}
        </span>
    </span>

    @if ($active)
        <span class="h-2 w-2 rounded-full bg-white/90 shadow-sm"></span>
    @else
        <span class="h-2 w-2 rounded-full bg-transparent transition duration-200 group-hover:bg-sky-300"></span>
    @endif
</a>
