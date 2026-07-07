@props(['label' => null, 'name' => null, 'error' => null])

<div class="w-full">
    @if($label)
    <label @if($name) for="{{ $name }}" @endif class="form-label">{{ $label }}</label>
    @endif
    <select
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        {{ $attributes->merge(['class' => 'input-field' . ($error ? ' input-error' : '')]) }}
    >{{ $slot }}</select>
    @if($error)<p class="form-error">{{ $error }}</p>@endif
</div>
