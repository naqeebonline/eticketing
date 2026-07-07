@extends('layouts.admin')
@section('title', 'Select Seats')
@section('header', 'New Booking')
@section('breadcrumb', 'Select seats')

@section('content')
<div x-data="adminSeatSelector()" class="max-w-4xl">
    <a href="{{ route('admin.bookings.create') }}" class="admin-row-action mb-6 inline-flex">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to departures
    </a>

    <x-ui.page-header
        title="Select seats"
        :subtitle="$schedule->route->departure_city.' → '.$schedule->route->destination_city.' · '.$schedule->departure_date->format('M d, Y').' · '.\Carbon\Carbon::parse($schedule->departure_time)->format('h:i A')"
    />

    <div class="admin-panel overflow-hidden p-0">
        <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/40 sm:px-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $schedule->vehicle->name ?? 'Bus' }} · {{ $schedule->vehicle->bus_number ?? '' }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $schedule->available_seats }} seats available on this trip</p>
                </div>
                <x-bus.seat-legend :show-luxury="true" compact />
            </div>
        </div>

        <div class="p-5 sm:p-8">
            <x-bus.interactive-seat-map :seat-rows="$seatMap['seat_rows'] ?? []" />
        </div>
    </div>

    <form method="POST" action="{{ route('admin.bookings.seats.hold', $schedule) }}" class="admin-panel mt-6 p-5 sm:p-6">
        @csrf
        <template x-for="id in selected" :key="id">
            <input type="hidden" name="seat_ids[]" :value="id">
        </template>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-sm text-slate-500">Selected seats</p>
                <p class="font-display text-lg font-bold text-slate-900 dark:text-white" x-text="selectedLabels() || '—'"></p>
                <div class="booking-seat-summary" x-show="selected.length > 0">
                    <template x-for="seat in selectedSeats()" :key="seat.id">
                        <span class="booking-seat-chip" :class="seat.type === 'luxury' && 'booking-seat-chip--luxury'">
                            <span x-text="'#' + seat.seat_number"></span>
                            <span class="opacity-60">·</span>
                            <span x-text="seat.type === 'luxury' ? 'Luxury' : 'Normal'"></span>
                            <span class="booking-seat-chip__fare" x-text="'PKR ' + seat.fare.toLocaleString('en-PK')"></span>
                        </span>
                    </template>
                </div>
                <p class="mt-3 text-sm font-semibold text-primary-600" x-text="totalFareLabel()"></p>
                <p class="mt-1 text-xs text-slate-400"><span x-text="selected.length">0</span> of {{ $schedule->available_seats }} available</p>
            </div>
            <x-ui.button type="submit" class="shrink-0" x-bind:disabled="selected.length === 0">
                Continue to passengers
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </x-ui.button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function adminSeatSelector() {
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
        selectedSeats() {
            return this.selected
                .map(id => this.seatsById[id])
                .filter(Boolean);
        },
        selectedLabels() {
            return this.selectedSeats()
                .map(seat => seat.seat_number)
                .join(', ');
        },
        totalFare() {
            return this.selectedSeats().reduce((sum, seat) => sum + (seat.fare ?? this.defaultFare), 0);
        },
        totalFareLabel() {
            const total = this.totalFare();
            return total > 0 ? 'Total · PKR ' + total.toLocaleString('en-PK') : 'PKR 0';
        },
    };
}
</script>
@endpush
