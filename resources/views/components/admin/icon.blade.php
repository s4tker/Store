@props([
    'tone' => 'blue',
    'size' => 'md',
])

@php
    $tones = [
        'blue' => 'bg-blue-50 text-blue-600 ring-blue-100',
        'indigo' => 'bg-indigo-50 text-indigo-600 ring-indigo-100',
        'violet' => 'bg-violet-50 text-violet-600 ring-violet-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
        'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
        'rose' => 'bg-rose-50 text-rose-600 ring-rose-100',
    ];

    $sizes = [
        'sm' => 'h-9 w-9 rounded-full',
        'md' => 'h-11 w-11 rounded-full',
        'lg' => 'h-14 w-14 rounded-full',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center justify-center shrink-0 ring-1',
    $tones[$tone] ?? $tones['blue'],
    $sizes[$size] ?? $sizes['md'],
]) }}>
    {{ $slot }}
</span>
