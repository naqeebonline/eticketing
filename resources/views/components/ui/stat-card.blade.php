@props(['label', 'value', 'trend' => null, 'color' => 'default'])

@php
$valueColor = match($color) {
    'primary' => 'text-primary-600 dark:text-primary-400',
    'success' => 'text-emerald-600 dark:text-emerald-500',
    default => 'text-slate-900 dark:text-white',
};
@endphp

<div class="stat-card animate-slide-up">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight {{ $valueColor }}">{{ $value }}</p>
            @if($trend)<p class="mt-1 text-xs text-slate-500">{{ $trend }}</p>@endif
        </div>
        @if(isset($icon))<div class="stat-card-icon">{!! $icon !!}</div>@endif
    </div>
</div>
