@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
$classes = match($variant) {
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'outline' => 'btn-outline',
    'danger' => 'btn-danger',
    'ghost' => 'btn-ghost',
    default => 'btn-primary',
};
$classes .= $size === 'sm' ? ' btn-sm' : ($size === 'lg' ? ' btn-lg' : '');
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
