@props(['variant' => 'neutral'])

@php
$class = match($variant) {
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
    'primary' => 'badge-primary',
    default => 'badge-neutral',
};
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</span>
