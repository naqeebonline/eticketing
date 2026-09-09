@props([
    'name',
    'label' => null,
    'placeholder' => 'Select…',
    'emptyText' => 'No results',
    'value' => '',
    'options' => [],
    'allowClear' => true,
    'wrapperClass' => 'min-w-[12rem]',
])

@php
    $normalized = collect($options)->map(function ($option) {
        if (is_array($option)) {
            return [
                'value' => (string) ($option['value'] ?? ''),
                'label' => (string) ($option['label'] ?? ''),
            ];
        }

        return [
            'value' => (string) data_get($option, 'value', ''),
            'label' => (string) data_get($option, 'label', ''),
        ];
    })->values()->all();
@endphp

<div
    class="{{ $wrapperClass }}"
    x-data="searchableSelect({
        value: @js((string) $value),
        options: @js($normalized),
        placeholder: @js($placeholder),
        emptyText: @js($emptyText),
    })"
    @keydown="onKeydown($event)"
    @click.outside="close()"
>
    @if($label)
    <label class="form-label">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="value">

    <div class="relative">
        <button
            type="button"
            class="searchable-select-trigger"
            :class="open ? 'searchable-select-trigger--open' : ''"
            @click="toggle()"
            :aria-expanded="open.toString()"
        >
            <span class="truncate" :class="value ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400'" x-text="selectedLabel"></span>
            <span class="ml-2 flex shrink-0 items-center gap-1">
                @if($allowClear)
                <span
                    x-show="value"
                    x-cloak
                    class="rounded p-0.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-200"
                    @click="clear($event)"
                    title="Clear"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </span>
                @endif
                <svg class="h-4 w-4 text-slate-400 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </span>
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="searchable-select-menu"
        >
            <div class="border-b border-slate-100 p-2 dark:border-slate-700">
                <input
                    type="search"
                    x-ref="searchInput"
                    x-model="search"
                    @input="onSearchInput()"
                    class="input-field w-full py-1.5 text-sm"
                    placeholder="Search…"
                    autocomplete="off"
                >
            </div>

            <ul class="max-h-56 overflow-y-auto py-1" role="listbox">
                <li>
                    <button
                        type="button"
                        class="searchable-select-option"
                        :class="value === '' ? 'searchable-select-option--active' : ''"
                        @click="select({ value: '', label: placeholder })"
                        x-text="placeholder"
                    ></button>
                </li>
                <template x-for="(option, index) in filteredOptions" :key="option.value + '-' + index">
                    <li>
                        <button
                            type="button"
                            class="searchable-select-option"
                            :class="{
                                'searchable-select-option--active': String(option.value) === String(value),
                                'searchable-select-option--highlight': index === highlightIndex,
                            }"
                            @click="select(option)"
                            @mouseenter="highlightIndex = index"
                            x-text="option.label"
                        ></button>
                    </li>
                </template>
                <li x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-slate-400" x-text="emptyText"></li>
            </ul>
        </div>
    </div>
</div>
