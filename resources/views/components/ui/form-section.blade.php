@props(['title', 'description' => null, 'accent' => false, 'icon' => null])

<div {{ $attributes->merge(['class' => $accent ? 'admin-form-section admin-form-section--accent' : 'admin-form-section']) }}>
    <div class="admin-form-section-title">
        @if($icon)
        <span class="admin-form-section-icon">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
        </span>
        @endif
        <div>
            <h2 class="admin-form-section-heading">{{ $title }}</h2>
            @if($description)<p class="admin-form-section-desc">{{ $description }}</p>@endif
        </div>
        @isset($actions)
        <div class="ml-auto shrink-0">{{ $actions }}</div>
        @endisset
    </div>
    {{ $slot }}
</div>
