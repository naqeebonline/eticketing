@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => '',
    'hint' => null,
    'error' => null,
])

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    @if($label)
    <label @if($name) for="{{ $name }}" @endif class="form-label">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        value="{{ $value }}"
        {{ $attributes->except(['class', 'value'])->merge(['class' => 'input-field' . ($error ? ' input-error' : '')]) }}
    />
    @if($error)
    <p class="form-error">{{ $error }}</p>
    @elseif($hint)
    <p class="form-hint">{{ $hint }}</p>
    @endif
</div>
