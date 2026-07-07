@props(['href', 'label', 'hint', 'icon'])

<a href="{{ $href }}" class="admin-quick-card">
    <span class="admin-quick-card-icon">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
        </svg>
    </span>
    <span>
        <span class="admin-quick-card-label">{{ $label }}</span>
        @if($hint)<span class="admin-quick-card-hint block">{{ $hint }}</span>@endif
    </span>
</a>
