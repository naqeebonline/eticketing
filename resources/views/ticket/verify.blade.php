@extends('layouts.app')
@section('title', 'Ticket Verification')

@section('content')
<div class="booking-flow-shell mx-auto max-w-lg py-8">
    <div class="mb-6 text-center">
        @if($isValid)
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-success-50 text-success-600 dark:bg-success-500/10">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Valid ticket</h1>
        @else
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-500/10">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Ticket not active</h1>
        @endif
        <p class="mt-1 font-mono text-sm text-slate-500">{{ $booking->booking_number }}</p>
    </div>

    <div class="ticket-verify-card">
        <div class="ticket-verify-card__header">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Trip</p>
                <p class="mt-1 text-lg font-bold text-slate-900 dark:text-white">
                    {{ $booking->schedule->route->departure_city }}
                    <span class="text-primary-500">→</span>
                    {{ $booking->schedule->route->destination_city }}
                </p>
            </div>
            <span class="ticket-verify-status ticket-verify-status--{{ $isValid ? 'valid' : 'invalid' }}">
                {{ ucfirst($booking->status->value ?? $booking->status) }}
            </span>
        </div>

        <dl class="ticket-verify-meta">
            <div>
                <dt>Date & time</dt>
                <dd>{{ $booking->schedule->departure_date->format('l, M d, Y') }} · {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</dd>
            </div>
            <div>
                <dt>Bus</dt>
                <dd>{{ $booking->schedule->vehicle->name ?? '—' }} ({{ $booking->schedule->vehicle->bus_number ?? '—' }})</dd>
            </div>
            <div>
                <dt>Total seats</dt>
                <dd>{{ $booking->passengers->count() }}</dd>
            </div>
            <div>
                <dt>Total fare</dt>
                <dd class="font-display text-primary-600">PKR {{ number_format($booking->total_amount) }}</dd>
            </div>
        </dl>

        <div class="ticket-verify-seats">
            <p class="ticket-verify-seats__title">Booked seats</p>
            @if($booking->passengers->isEmpty())
            <p class="text-sm text-slate-500">Koi active seat nahi — sab cancel ho chuki hain.</p>
            @else
            <div class="ticket-seat-table">
                <div class="ticket-seat-table__head ticket-seat-table__head--4col">
                    <span>#</span>
                    <span>Seat</span>
                    <span>Type</span>
                    <span class="text-right">Fare</span>
                </div>
                @foreach($booking->passengers as $index => $passenger)
                @php
                    $seatType = $passenger->seat?->type ?? 'normal';
                    $isLuxury = $seatType === 'luxury';
                @endphp
                <div class="ticket-seat-table__row ticket-seat-table__row--4col">
                    <span class="text-slate-400">{{ $index + 1 }}</span>
                    <span class="ticket-seat-table__num">{{ $passenger->seat?->seat_number ?? '—' }}</span>
                    <span class="ticket-seat-table__type {{ $isLuxury ? 'ticket-seat-table__type--luxury' : '' }}">
                        {{ $passenger->seat?->rowTypeLabel() ?? 'Normal' }}
                    </span>
                    <span class="ticket-seat-table__fare">PKR {{ number_format($passenger->fare) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">Verified at {{ now()->format('M d, Y · h:i A') }}</p>
</div>
@endsection
