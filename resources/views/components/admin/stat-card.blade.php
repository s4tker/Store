@props([
    'label',
    'value',
    'caption' => null,
    'tone' => 'blue',
])

<article {{ $attributes->class('admin-stat-card') }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="admin-card-kicker">{{ $label }}</p>
            <p class="mt-2 text-xl font-semibold tracking-tight text-slate-900 md:text-[1.35rem]">{{ $value }}</p>
        </div>

        @isset($icon)
            <x-admin.icon :tone="$tone" size="sm">
                {{ $icon }}
            </x-admin.icon>
        @endisset
    </div>

    @if($caption)
        <p class="mt-2 text-xs text-slate-500">{{ $caption }}</p>
    @endif
</article>
