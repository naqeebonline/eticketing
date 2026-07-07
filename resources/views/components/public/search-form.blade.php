@props([
    'action' => route('search'),
    'from' => '',
    'to' => '',
    'date' => today()->format('Y-m-d'),
    'compact' => false,
    'variant' => 'default',
])

@php
    $cities = app(\App\Services\City\CityService::class)->active();
    $isHero = $variant === 'hero';
    $fieldClass = $compact ? 'input-field' : ($isHero ? 'eticket-field' : 'input-field-lg');
    $formClass = match (true) {
        $isHero => 'eticket-search-panel',
        $compact => 'search-card p-4',
        default => 'search-card',
    };
@endphp

<form action="{{ $action }}" method="GET" {{ $attributes->merge(['class' => $formClass]) }}>
    @if($isHero)
    <div class="eticket-search-panel__head">
        <div class="eticket-search-panel__tab eticket-search-panel__tab--active">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 001 1h1a1 1 0 001-1v-1h8v1a1 1 0 001 1h1a1 1 0 001-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-2.21-1.79-4-4-4H8C5.79 2 4 3.79 4 6v10z"/></svg>
            Bus Ticket
        </div>
        <p class="eticket-search-panel__hint">Search routes · Pick seats · Get instant e-ticket</p>
    </div>

    <div class="eticket-search-panel__body">
        <div class="eticket-search-panel__fields">
            <div class="eticket-search-panel__row">
                <div class="eticket-search-panel__field">
                    <label for="from" class="eticket-search-panel__label">From</label>
                    <select id="from" name="from" required class="{{ $fieldClass }}">
                        <option value="">Departure city</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->name }}" @selected(old('from', $from) === $city->name)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button
                    type="button"
                    class="eticket-search-panel__swap"
                    aria-label="Swap cities"
                    onclick="const f=document.getElementById('from');const t=document.getElementById('to');const v=f.value;f.value=t.value;t.value=v;"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                </button>

                <div class="eticket-search-panel__field">
                    <label for="to" class="eticket-search-panel__label">To</label>
                    <select id="to" name="to" required class="{{ $fieldClass }}">
                        <option value="">Destination city</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->name }}" @selected(old('to', $to) === $city->name)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="eticket-search-panel__field">
                <label for="date" class="eticket-search-panel__label">Travel date</label>
                <input type="date" id="date" name="date" required min="{{ today()->format('Y-m-d') }}" value="{{ $date }}" class="{{ $fieldClass }}">
            </div>
        </div>

        <button type="submit" class="eticket-search-panel__submit">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Search buses
        </button>
    </div>
    @else
    @unless($compact)
    <p class="mb-4 text-sm font-semibold text-slate-700 dark:text-slate-300">Where are you traveling?</p>
    @endunless

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="relative text-left">
            <label for="from" class="form-label flex items-center gap-1.5">
                <svg class="h-4 w-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                From
            </label>
            <select id="from" name="from" required class="{{ $fieldClass }}">
                <option value="">— Select city —</option>
                @foreach($cities as $city)
                <option value="{{ $city->name }}" @selected(old('from', $from) === $city->name)>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="relative text-left">
            <label for="to" class="form-label flex items-center gap-1.5">
                <svg class="h-4 w-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                To
            </label>
            <select id="to" name="to" required class="{{ $fieldClass }}">
                <option value="">— Select city —</option>
                @foreach($cities as $city)
                <option value="{{ $city->name }}" @selected(old('to', $to) === $city->name)>{{ $city->name }}</option>
                @endforeach
            </select>
            @unless($compact)
            <button
                type="button"
                class="absolute right-0 top-8 hidden rounded-full border border-slate-200 bg-white p-2 text-primary-600 shadow-sm hover:bg-primary-50 lg:inline-flex dark:border-slate-600 dark:bg-slate-800"
                aria-label="Swap cities"
                onclick="const f=document.getElementById('from');const t=document.getElementById('to');const v=f.value;f.value=t.value;t.value=v;"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
            </button>
            @endunless
        </div>
        <div class="text-left">
            <label for="date" class="form-label flex items-center gap-1.5">
                <svg class="h-4 w-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Travel date
            </label>
            <input type="date" id="date" name="date" required min="{{ today()->format('Y-m-d') }}" value="{{ $date }}" class="{{ $fieldClass }}">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary btn-lg w-full shadow-lg shadow-primary-500/25">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Search buses
            </button>
        </div>
    </div>
    @endif
</form>
