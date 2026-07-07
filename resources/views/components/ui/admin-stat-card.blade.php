@props(['label', 'value', 'accent' => 'default', 'trend' => null])

@php
$valueColor = match($accent) {
    'primary' => 'text-primary-600 dark:text-primary-400',
    'success' => 'text-emerald-600 dark:text-emerald-500',
    'warning' => 'text-amber-600 dark:text-amber-500',
    default => 'text-slate-900 dark:text-white',
};
@endphp

<div class="admin-stat-card admin-stat-card--{{ $accent }} animate-slide-up">
    <div class="flex items-start justify-between gap-4 pl-2">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 font-display text-3xl font-bold tracking-tight {{ $valueColor }}">{{ $value }}</p>
            @if($trend)<p class="mt-1 text-xs text-slate-500">{{ $trend }}</p>@endif
        </div>
        @if(isset($icon))
        <div class="admin-stat-card-icon">{!! $icon !!}</div>
        @endif
    </div>
</div>
