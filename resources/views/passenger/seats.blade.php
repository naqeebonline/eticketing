@extends('layouts.app')
@section('title', 'Select Seats')

@section('content')
<div x-data="seatSelector()" class="booking-flow-shell">
    <x-public.booking-steps :current="2" />

    <div class="booking-flow-grid">
        <div>
            <div class="booking-page-header">
                <h1 class="booking-page-title">Choose your seats</h1>
                <div class="booking-route-chip">
                    <span class="booking-route-cities">{{ $schedule->route->departure_city }} → {{ $schedule->route->destination_city }}</span>
                    <span class="booking-route-meta">{{ $schedule->departure_date->format('M d, Y') }} · {{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                </div>
                <p class="mt-2 text-sm text-slate-500">Tap multiple seats to book together. Up to {{ $schedule->available_seats }} seat(s) on this trip.</p>
            </div>

            <div class="search-card p-6 sm:p-8">
                <x-bus.seat-legend class="mb-8" />

                <x-bus.interactive-seat-map :seat-rows="$seatMap['seat_rows'] ?? []" />
            </div>

            <form method="POST" action="{{ route('book.seats.hold', $schedule->uuid) }}" class="mt-6 lg:hidden">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="seat_ids[]" :value="id">
                </template>
                <div class="booking-sticky-bar flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Selected</p>
                        <p class="font-display text-lg font-bold text-slate-900 dark:text-white" x-text="selectedLabels() || '—'"></p>
                        <p class="text-sm font-semibold text-primary-600" x-text="totalFareLabel()"></p>
                    </div>
                    <x-ui.button type="submit" size="lg" class="w-full sm:w-auto" x-bind:disabled="selected.length === 0">
                        Continue (<span x-text="selected.length">0</span>)
                    </x-ui.button>
                </div>
            </form>
        </div>

        <div class="hidden lg:block">
            <x-public.trip-summary
                :from="$schedule->route->departure_city"
                :to="$schedule->route->destination_city"
                :date="$schedule->departure_date->format('l, M d, Y')"
                :time="\Carbon\Carbon::parse($schedule->departure_time)->format('h:i A')"
                :bus="($schedule->vehicle->name ?? 'Bus').' · '.($schedule->vehicle->bus_number ?? '')"
            >
                <div class="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">
                    <p class="text-xs text-slate-500">Selected seats</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-white" x-text="selectedLabels() || 'None yet'"></p>
                    <p class="mt-3 text-xs text-slate-500">Estimated total</p>
                    <p class="font-display text-2xl font-bold text-primary-600 dark:text-primary-400" x-text="totalFareLabel()"></p>
                    <p class="mt-1 text-xs text-slate-400"><span x-text="selected.length">0</span> of {{ $schedule->available_seats }} available</p>
                </div>

                <form method="POST" action="{{ route('book.seats.hold', $schedule->uuid) }}" class="mt-5 border-t border-slate-100 pt-5 dark:border-slate-800">
                    @csrf
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="seat_ids[]" :value="id">
                    </template>
                    <x-ui.button type="submit" class="btn-sidebar-action w-full" x-bind:disabled="selected.length === 0">
                        Continue to passengers
                    </x-ui.button>
                </form>
            </x-public.trip-summary>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function seatSelector() {
    return {
        seatsById: @js($seatsById),
        defaultFare: @js((float) $schedule->fare),
        maxSeats: @js((int) $schedule->available_seats),
        selected: @js($initialSelected),
        toggleSeat(id) {
            const seat = this.seatsById[id];
            if (!seat || seat.status !== 'available') {
                return;
            }
            const i = this.selected.indexOf(id);
            if (i > -1) {
                this.selected.splice(i, 1);
                return;
            }
            if (this.selected.length >= this.maxSeats) {
                return;
            }
            this.selected.push(id);
        },
        selectedLabels() {
            return this.selected
                .map(id => this.seatsById[id]?.seat_number)
                .filter(Boolean)
                .join(', ');
        },
        totalFare() {
            return this.selected.reduce((sum, id) => {
                const seat = this.seatsById[id];
                return sum + (seat?.fare ?? this.defaultFare);
            }, 0);
        },
        totalFareLabel() {
            const total = this.totalFare();
            return total > 0 ? 'PKR ' + total.toLocaleString('en-PK') : 'PKR 0';
        },
    };
}
</script>
@endpush
