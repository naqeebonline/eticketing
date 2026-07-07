@props(['size' => 'md'])

@php
    $box = match ($size) {
        'sm' => 'h-9 w-9',
        'lg' => 'h-14 w-14',
        default => 'h-11 w-11',
    };
    $icon = match ($size) {
        'sm' => 'h-5 w-5',
        'lg' => 'h-8 w-8',
        default => 'h-6 w-6',
    };
@endphp

<span {{ $attributes->merge(['class' => "gov-emblem $box"]) }} aria-hidden="true">
    <svg class="{{ $icon }}" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 001 1h1a1 1 0 001-1v-1h8v1a1 1 0 001 1h1a1 1 0 001-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-2.21-1.79-4-4-4H8C5.79 2 4 3.79 4 6v10z"/></svg>
</span>
