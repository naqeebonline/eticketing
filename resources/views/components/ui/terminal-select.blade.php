@props([
    'label' => 'Terminal / Adda',
    'name' => 'terminal_id',
    'value' => '',
    'required' => false,
    'hint' => null,
    'terminals' => null,
])

@php
    $terminalList = $terminals ?? app(\App\Services\Terminal\TerminalService::class)->active();
@endphp

<div>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'input-field'.($errors->has($name) ? ' input-error' : '')]) }}
    >
        <option value="">— Select terminal / adda —</option>
        @foreach($terminalList as $terminal)
        <option value="{{ $terminal->id }}" @selected((string) old($name, $value) === (string) $terminal->id)>
            {{ $terminal->name }} ({{ $terminal->city }})
        </option>
        @endforeach
    </select>
    @if($hint)<p class="form-hint">{{ $hint }}</p>@endif
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
