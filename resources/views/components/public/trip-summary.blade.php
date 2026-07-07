@props([
    'from' => null,
    'to' => null,
    'date' => null,
    'time' => null,
    'bus' => null,
    'seats' => null,
    'amount' => null,
])

<aside {{ $attributes->merge(['class' => 'trip-summary-card']) }}>
    <p class="trip-summary-label">Your trip</p>
    @if($from && $to)
    <p class="mt-3 font-display text-lg font-bold text-slate-900 dark:text-white">
        {{ $from }} <span class="text-primary-500">→</span> {{ $to }}
    </p>
    @endif
    <dl class="mt-4 space-y-3 text-sm">
        @if($date)
        <div>
            <dt class="text-slate-500">Date</dt>
            <dd class="mt-0.5 font-semibold text-slate-900 dark:text-white">{{ $date }}</dd>
        </div>
        @endif
        @if($time)
        <div>
            <dt class="text-slate-500">Departure</dt>
            <dd class="mt-0.5 font-semibold text-slate-900 dark:text-white">{{ $time }}</dd>
        </div>
        @endif
        @if($bus)
        <div>
            <dt class="text-slate-500">Bus</dt>
            <dd class="mt-0.5 font-semibold text-slate-900 dark:text-white">{{ $bus }}</dd>
        </div>
        @endif
        @if($seats)
        <div>
            <dt class="text-slate-500">Seats</dt>
            <dd class="mt-0.5 font-semibold text-slate-900 dark:text-white">{{ $seats }}</dd>
        </div>
        @endif
    </dl>
    @if($amount)
    <div class="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">
        <p class="text-xs text-slate-500">Estimated total</p>
        <p class="font-display text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $amount }}</p>
    </div>
    @endif
    {{ $slot ?? '' }}
</aside>
