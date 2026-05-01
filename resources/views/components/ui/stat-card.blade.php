@props([
    'label',
    'value',
    'description' => null,
    'tone' => 'sky',
])

@php
    $tones = [
        'sky' => [
            'icon' => 'bg-sky-50 text-sky-600 ring-sky-100',
            'value' => 'text-slate-900',
        ],
        'green' => [
            'icon' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
            'value' => 'text-emerald-700',
        ],
        'red' => [
            'icon' => 'bg-red-50 text-red-600 ring-red-100',
            'value' => 'text-red-700',
        ],
        'amber' => [
            'icon' => 'bg-amber-50 text-amber-600 ring-amber-100',
            'value' => 'text-amber-700',
        ],
        'slate' => [
            'icon' => 'bg-slate-50 text-slate-600 ring-slate-100',
            'value' => 'text-slate-900',
        ],
    ];

    $style = $tones[$tone] ?? $tones['sky'];
@endphp

<div {{ $attributes->merge([
    'class' => 'group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md'
]) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-sm font-medium text-slate-500">
                {{ $label }}
            </div>

            <div class="mt-2 text-2xl font-bold tracking-tight {{ $style['value'] }}">
                {{ $value }}
            </div>

            @if ($description)
                <p class="mt-1 text-xs leading-5 text-slate-500">
                    {{ $description }}
                </p>
            @endif
        </div>

        @if (isset($icon))
            <div class="rounded-2xl p-3 ring-1 {{ $style['icon'] }}">
                {{ $icon }}
            </div>
        @else
            <div class="rounded-2xl p-3 ring-1 {{ $style['icon'] }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 3v18" />
                    <path d="M3 12h18" />
                </svg>
            </div>
        @endif
    </div>
</div>