@extends('layouts.admin')
@section('title', 'Booking Details')
@section('header', 'Booking Details')
@section('breadcrumb', $booking->booking_number)

@section('content')
<a href="{{ route('admin.bookings.index') }}" class="admin-row-action mb-6 inline-flex">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to bookings
</a>

<div class="admin-detail-hero mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">Reservation</p>
            <h2 class="mt-1 font-mono text-2xl font-bold text-slate-900 dark:text-white">{{ $booking->booking_number }}</h2>
            <p class="mt-2 text-sm text-slate-500">Booked {{ $booking->created_at->format('M d, Y · h:i A') }}</p>
        </div>
        <x-ui.badge :variant="match($booking->status->value ?? $booking->status) {
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'pending', 'held' => 'warning',
            default => 'primary',
        }" class="!px-3 !py-1 !text-sm">{{ ucfirst($booking->status->value ?? $booking->status) }}</x-ui.badge>
    </div>
    <p class="admin-detail-amount mt-6">PKR {{ number_format($booking->total_amount) }}</p>
    <p class="mt-1 text-sm text-slate-500">Paid: PKR {{ number_format($booking->paid_amount) }} · Payment: {{ ucfirst($booking->payment_status->value ?? $booking->payment_status) }}</p>
    @if($booking->canReceiveCounterPayment())
    <form method="POST" action="{{ route('admin.bookings.confirm', $booking->uuid) }}" class="mt-5" data-confirm="Cash payment receive karke booking confirm karein?" data-confirm-title="Confirm booking" data-confirm-variant="warning" data-confirm-label="Receive payment & confirm">
        @csrf
        <x-ui.button type="submit">Receive payment & confirm</x-ui.button>
    </form>
    @endif
    <div class="mt-4 flex flex-wrap gap-3 print:hidden">
        <x-ui.button href="{{ route('admin.bookings.receipt', $booking->uuid) }}" variant="secondary">
            View receipt & QR
        </x-ui.button>
    </div>
</div>

<div class="admin-detail-grid">
    <div class="admin-detail-card lg:col-span-2">
        <h3 class="admin-panel-title">Trip details</h3>
        <dl class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="admin-detail-label">Route</dt>
                <dd class="admin-detail-value mt-2">
                    <span class="admin-route-pill text-sm">
                        {{ $booking->schedule->route->departure_city }}
                        <span class="admin-route-arrow">→</span>
                        {{ $booking->schedule->route->destination_city }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="admin-detail-label">Departure</dt>
                <dd class="admin-detail-value">{{ $booking->schedule->departure_date->format('l, M d, Y') }}</dd>
                <dd class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</dd>
            </div>
            <div>
                <dt class="admin-detail-label">Bus</dt>
                <dd class="admin-detail-value">{{ $booking->schedule->vehicle->name ?? '—' }}</dd>
                @if($booking->schedule->vehicle)
                <dd class="text-sm text-slate-500">{{ $booking->schedule->vehicle->bus_number }}</dd>
                @endif
            </div>
            <div>
                <dt class="admin-detail-label">Active seats</dt>
                <dd class="admin-detail-value">{{ $booking->passengers->whereNull('cancelled_at')->count() }} / {{ $booking->passengers->count() }}</dd>
            </div>
            @php
                $activePassengers = $booking->passengers->whereNull('cancelled_at');
                $primaryPassenger = $activePassengers->first() ?? $booking->passengers->first();
                $maleCount = $activePassengers->where('gender', 'male')->where('passenger_type', 'adult')->count();
                $femaleCount = $activePassengers->where('gender', 'female')->where('passenger_type', 'adult')->count();
                $childCount = $activePassengers->where('passenger_type', 'child')->count();
                $canCancelSeats = $booking->schedule->allowsSeatCancellation()
                    && ! in_array($booking->status->value ?? $booking->status, ['cancelled', 'completed'], true);
            @endphp
            <div>
                <dt class="admin-detail-label">CNIC</dt>
                <dd class="admin-detail-value">{{ $primaryPassenger?->cnic ?? '—' }}</dd>
            </div>
            <div>
                <dt class="admin-detail-label">Travelers</dt>
                <dd class="admin-detail-value">
                    {{ collect([
                        $maleCount > 0 ? $maleCount.' Male' : null,
                        $femaleCount > 0 ? $femaleCount.' Female' : null,
                        $childCount > 0 ? $childCount.' Child' : null,
                    ])->filter()->join(', ') ?: '—' }}
                </dd>
            </div>
            <div>
                <dt class="admin-detail-label">Source</dt>
                <dd class="admin-detail-value">{{ ucfirst($booking->booking_source) }}</dd>
            </div>
        </dl>
    </div>

    <div class="admin-detail-card">
        <h3 class="admin-panel-title">Summary</h3>
        <ul class="mt-5 space-y-4">
            <li class="admin-timeline-item border-primary-200 dark:border-primary-800">
                <div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Booking created</p>
                    <p class="text-xs text-slate-500">{{ $booking->created_at->diffForHumans() }}</p>
                </div>
            </li>
            <li class="admin-fleet-stat !rounded-lg">
                <span class="text-sm text-slate-600">Total fare</span>
                <span class="font-display font-bold text-primary-600">PKR {{ number_format($booking->total_amount) }}</span>
            </li>
            <li class="admin-fleet-stat !rounded-lg">
                <span class="text-sm text-slate-600">Amount paid</span>
                <span class="font-semibold">PKR {{ number_format($booking->paid_amount) }}</span>
            </li>
        </ul>
    </div>
</div>

@php
    $cancellablePassengers = $booking->passengers->whereNull('cancelled_at')->values();
@endphp

<div
    class="admin-panel admin-table mt-6"
    x-data="{
        selected: [],
        cancellableIds: @js($cancellablePassengers->pluck('id')->map(fn ($id) => (int) $id)->values()),
        toggle(id) {
            const i = this.selected.indexOf(id);
            if (i > -1) this.selected.splice(i, 1);
            else this.selected.push(id);
        },
        toggleAll() {
            this.selected = this.selected.length === this.cancellableIds.length ? [] : [...this.cancellableIds];
        },
        get allSelected() {
            return this.cancellableIds.length > 0 && this.selected.length === this.cancellableIds.length;
        },
        async submitCancellation(event) {
            if (this.selected.length === 0) {
                event.preventDefault();
                await this.$store.dialog.alert({
                    title: 'Select seats',
                    message: 'Kam az kam ek seat select karein.',
                    variant: 'warning',
                });
                return;
            }
            event.preventDefault();
            const ok = await this.$store.dialog.confirm({
                title: 'Cancel seats',
                message: this.selected.length + ' seat(s) cancel karein? Paid seats ki refund hogi.',
                variant: 'danger',
                confirmLabel: 'Yes, cancel',
                cancelLabel: 'Keep seats',
            });
            if (ok) {
                event.target.submit();
            }
        },
    }"
>
    <div class="admin-panel-header flex flex-wrap items-end justify-between gap-4">
        <div>
            <h3 class="admin-panel-title">Passengers & seats</h3>
            @if($canCancelSeats)
            <p class="mt-1 text-sm text-slate-500">Multiple seats select karein — paid seats refund, unpaid cancel. Sirf departure se pehle.</p>
            @else
            <p class="mt-1 text-sm text-amber-600 dark:text-amber-400">Cancellation band — schedule depart ho chuki hai ya trip complete/cancelled hai.</p>
            @endif
        </div>
        @if($canCancelSeats && $cancellablePassengers->isNotEmpty())
        <form
            method="POST"
            action="{{ route('admin.bookings.passengers.cancel', $booking->uuid) }}"
            class="flex flex-wrap items-center gap-3"
            x-on:submit="submitCancellation($event)"
        >
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="passenger_ids[]" :value="id">
            </template>
            <span class="text-sm text-slate-500" x-show="selected.length > 0" x-text="selected.length + ' selected'"></span>
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-950/60"
                x-bind:disabled="selected.length === 0"
            >
                Cancel selected
            </button>
        </form>
        @endif
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    @if($canCancelSeats && $cancellablePassengers->isNotEmpty())
                    <th class="w-10">
                        <input
                            type="checkbox"
                            class="rounded border-slate-300 text-primary-600"
                            x-bind:checked="allSelected"
                            x-on:change="toggleAll()"
                            aria-label="Select all active seats"
                        >
                    </th>
                    @endif
                    <th>Name</th><th>Seat</th><th>Gender</th><th>Type</th><th class="text-right">Fare</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->passengers as $passenger)
                <tr @class(['opacity-60' => $passenger->isCancelled()])>
                    @if($canCancelSeats && $cancellablePassengers->isNotEmpty())
                    <td>
                        @if(! $passenger->isCancelled())
                        <input
                            type="checkbox"
                            class="rounded border-slate-300 text-primary-600"
                            value="{{ $passenger->id }}"
                            x-bind:checked="selected.includes({{ $passenger->id }})"
                            x-on:change="toggle({{ $passenger->id }})"
                            aria-label="Select seat {{ $passenger->seat?->seat_number }}"
                        >
                        @endif
                    </td>
                    @endif
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $passenger->full_name }}</td>
                    <td>
                        @if($passenger->seat)
                        <span class="rounded-lg bg-primary-50 px-2.5 py-1 font-mono text-xs font-bold text-primary-700 dark:bg-primary-500/10 dark:text-primary-400">{{ $passenger->seat->seat_number }}</span>
                        @else
                        —
                        @endif
                    </td>
                    <td>{{ ucfirst($passenger->gender) }}</td>
                    <td>{{ ucfirst($passenger->passenger_type) }}</td>
                    <td class="text-right font-semibold">PKR {{ number_format($passenger->fare) }}</td>
                    <td>
                        @if($passenger->isCancelled())
                        <x-ui.badge variant="danger">Cancelled</x-ui.badge>
                        @else
                        <x-ui.badge variant="success">Active</x-ui.badge>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($booking->passengerCancellations->isNotEmpty())
<div class="admin-panel admin-table mt-6">
    <div class="admin-panel-header">
        <h3 class="admin-panel-title">Cancellation log</h3>
        <p class="mt-1 text-sm text-slate-500">Seat amount, who cancelled, and date for each action.</p>
    </div>
    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Seat</th>
                    <th class="text-right">Amount</th>
                    <th>Action</th>
                    <th>Cancelled by</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->passengerCancellations as $log)
                <tr>
                    <td>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $log->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $log->created_at->format('h:i A') }}</p>
                    </td>
                    <td>
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            {{ $log->seat_number ?? '—' }}
                        </span>
                    </td>
                    <td class="text-right font-semibold">PKR {{ number_format($log->fare) }}</td>
                    <td>
                        <x-ui.badge :variant="$log->action === 'refunded' ? 'warning' : 'neutral'">
                            {{ $log->actionLabel() }}
                        </x-ui.badge>
                    </td>
                    <td>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $log->cancelledByUser?->name ?? '—' }}</p>
                        <p class="text-xs text-slate-500">{{ $log->cancelledByUser?->email }}</p>
                    </td>
                    <td class="text-sm text-slate-500">{{ $log->reason ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
