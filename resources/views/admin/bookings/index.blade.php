@extends('layouts.admin')
@section('title', 'Bookings')
@section('header', 'Bookings')
@section('breadcrumb', 'Passenger reservations')

@section('content')
@php
    $departureDate = request('departure_date', $filters['departure_date'] ?? today()->format('Y-m-d'));
@endphp

<x-ui.page-header title="Bookings" subtitle="Bus-wise reservations — filter by vehicle, route, aur departure date">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.bookings.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New booking
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <x-admin.filter-bar>
        <div>
            <label class="form-label">Bus / Vehicle</label>
            <select name="vehicle_id" class="input-field min-w-[10rem]">
                <option value="">All buses</option>
                @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" @selected((string) request('vehicle_id') === (string) $vehicle->id)>
                    {{ $vehicle->name }}@if($vehicle->bus_number) · {{ $vehicle->bus_number }}@endif
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Route</label>
            <select name="route_id" class="input-field min-w-[10rem]">
                <option value="">All routes</option>
                @foreach($routes as $route)
                <option value="{{ $route->id }}" @selected((string) request('route_id') === (string) $route->id)>
                    {{ $route->departure_city }} → {{ $route->destination_city }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Departure date</label>
            <input type="date" name="departure_date" value="{{ $departureDate }}" class="input-field">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="input-field">
                <option value="">All statuses</option>
                @foreach(['pending', 'held', 'confirmed', 'cancelled', 'completed'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Payment</label>
            <select name="payment_status" class="input-field">
                <option value="">All</option>
                @foreach(['pending', 'partial', 'paid', 'refunded'] as $paymentStatus)
                <option value="{{ $paymentStatus }}" @selected(request('payment_status') === $paymentStatus)>{{ ucfirst($paymentStatus) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Search</label>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Booking #, name, CNIC" class="input-field min-w-[12rem]">
        </div>
    </x-admin.filter-bar>

    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Departure</th>
                    <th>Route</th>
                    <th>Passengers</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $lastVehicleKey = null; @endphp
                @forelse($bookings as $booking)
                @php
                    $vehicle = $booking->schedule->vehicle;
                    $vehicleKey = $vehicle?->id ?? 'none';
                    $showBusHeader = $lastVehicleKey !== $vehicleKey;
                    $lastVehicleKey = $vehicleKey;
                    $primaryPassenger = $booking->passengers->firstWhere('cancelled_at', null) ?? $booking->passengers->first();
                @endphp
                @if($showBusHeader && ! request('vehicle_id'))
                <tr class="bg-slate-50 dark:bg-slate-800/60">
                    <td colspan="7" class="!py-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 text-primary-600 dark:bg-primary-900/40">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $vehicle->name ?? 'No vehicle' }}</p>
                                @if($vehicle?->bus_number)
                                <p class="text-xs text-slate-500">{{ $vehicle->bus_number }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                <tr>
                    <td>
                        <p class="font-mono text-sm font-semibold text-slate-900 dark:text-white">{{ $booking->booking_number }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->created_at->format('M d · h:i A') }}</p>
                        @if($primaryPassenger)
                        <p class="mt-1 text-xs text-slate-500">{{ $primaryPassenger->full_name }}</p>
                        @endif
                    </td>
                    <td>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $booking->schedule->departure_date->format('M d, Y') }}</p>
                        <p class="text-xs tabular-nums text-slate-500">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A') }}</p>
                    </td>
                    <td>
                        <span class="admin-route-pill">
                            {{ $booking->schedule->route->departure_city ?? '' }}
                            <span class="admin-route-arrow">→</span>
                            {{ $booking->schedule->route->destination_city ?? '' }}
                        </span>
                    </td>
                    <td>
                        <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $booking->active_passengers_count ?? $booking->passengers->whereNull('cancelled_at')->count() }} seat(s)
                        </span>
                    </td>
                    <td>
                        <p class="font-display font-bold text-slate-900 dark:text-white">PKR {{ number_format($booking->total_amount) }}</p>
                        <p class="text-xs text-slate-500">Paid: PKR {{ number_format($booking->paid_amount) }}</p>
                    </td>
                    <td>
                        <x-ui.badge :variant="match($booking->status->value ?? $booking->status) {
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            'pending', 'held' => 'warning',
                            default => 'neutral',
                        }">{{ ucfirst($booking->status->value ?? $booking->status) }}</x-ui.badge>
                        <p class="mt-1 text-xs text-slate-500">{{ ucfirst($booking->payment_status->value ?? $booking->payment_status) }}</p>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.bookings.show', $booking->uuid) }}" class="admin-row-action">
                            View
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <x-ui.empty-state title="No bookings found" description="Filters change karein ya nayi counter booking add karein.">
                        <x-slot:action><x-ui.button href="{{ route('admin.bookings.create') }}">New booking</x-ui.button></x-slot:action>
                    </x-ui.empty-state>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
