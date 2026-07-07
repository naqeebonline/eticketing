@props(['label' => null, 'name' => null, 'error' => null, 'rows' => 3])

<div class="w-full">
    @if($label)<label @if($name) for="{{ $name }}" @endif class="form-label">{{ $label }}</label>@endif
    <textarea
        rows="{{ $rows }}"
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        {{ $attributes->merge(['class' => 'input-field' . ($error ? ' input-error' : '')]) }}
    >{{ $slot }}</textarea>
    @if($error)<p class="form-error">{{ $error }}</p>@endif
</div>
