@props([
    'label',
    'name',
    'value' => '',
    'required' => false,
    'hint' => null,
    'cities' => null,
    'inputClass' => 'input-field',
])

@php
    $cityList = $cities ?? app(\App\Services\City\CityService::class)->active();
@endphp

<div>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => $inputClass.($errors->has($name) ? ' input-error' : '')]) }}
    >
        <option value="">— Select city —</option>
        @foreach($cityList as $city)
        <option value="{{ $city->name }}" @selected(old($name, $value) === $city->name)>{{ $city->name }}</option>
        @endforeach
    </select>
    @if($hint)<p class="form-hint">{{ $hint }}</p>@endif
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
