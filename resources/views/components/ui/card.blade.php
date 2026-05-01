@props([
    'padding' => 'p-5',
])

<div {{ $attributes->merge([
    'class' => 'rounded-2xl border border-slate-200/80 bg-white shadow-sm transition'
]) }}>
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>