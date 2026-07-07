@props(['variant' => 'success'])

@php
$styles = match($variant) {
    'success' => 'border-success-500/20 bg-success-50 text-success-800 dark:bg-success-500/10 dark:text-success-400',
    'error' => 'border-danger-500/20 bg-danger-50 text-danger-800 dark:bg-danger-500/10 dark:text-danger-400',
    'warning' => 'border-warning-500/20 bg-warning-50 text-warning-800 dark:bg-warning-500/10 dark:text-warning-500',
    'info' => 'border-primary-500/20 bg-primary-50 text-primary-800 dark:bg-primary-500/10 dark:text-primary-400',
    default => 'border-slate-200 bg-slate-50 text-slate-800',
};
@endphp

<div {{ $attributes->merge(['class' => "animate-fade-in rounded-lg border px-4 py-3 text-sm {$styles}", 'role' => 'alert']) }}>
    {{ $slot }}
</div>
