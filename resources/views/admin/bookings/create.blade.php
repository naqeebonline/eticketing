@extends('layouts.admin')
@section('title', 'New Booking')
@section('header', 'New Booking')
@section('breadcrumb', 'Counter sale')

@section('content')
<x-ui.page-header title="New booking" subtitle="Select a departure to sell seats at the counter">
    <x-slot:actions>
        <x-ui.button href="{{ route('admin.bookings.index') }}" variant="secondary">Back to bookings</x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

<div class="admin-panel admin-table">
    <x-admin.filter-bar>
        <div>
            <label class="form-label">Departure date</label>
            <input type="date" name="date" value="{{ $date }}" class="input-field" min="{{ today()->format('Y-m-d') }}">
        </div>
    </x-admin.filter-bar>

    <div class="table-wrap border-0 rounded-none shadow-none">
        <table class="table-modern">
            <thead>
                <tr><th>Date</th><th>Time</th><th>Route</th><th>Vehicle</th><th>Fare</th><th>Seats</th><th class="text-right">Action</th></tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    <td class="font-semibold text-slate-900 dark:text-white">{{ $schedule->departure_date->format('M d, Y') }}</td>
                    <td class="tabular-nums">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</td>
                    <td>
                        <span class="admin-route-pill">
                            {{ $schedule->route->departure_city }}
                            <span class="admin-route-arrow">→</span>
                            {{ $schedule->route->destination_city }}
                        </span>
                    </td>
                    <td>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ $schedule->vehicle->name ?? '—' }}</p>
                        @if($schedule->vehicle?->bus_number)
                        <p class="text-xs text-slate-500">{{ $schedule->vehicle->bus_number }}</p>
                        @endif
                    </td>
                    <td class="font-semibold">PKR {{ number_format($schedule->fare) }}</td>
                    <td>
                        <span class="rounded-lg bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">{{ $schedule->available_seats }} left</span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.bookings.seats', $schedule) }}" class="admin-row-action">
                            Select seats
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <x-ui.empty-state title="No departures available" description="Create a schedule with available seats first.">
                        <x-slot:action><x-ui.button href="{{ route('admin.schedules.create') }}">Add schedule</x-ui.button></x-slot:action>
                    </x-ui.empty-state>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $schedules->links() }}</div>
    @endif
</div>
@endsection
