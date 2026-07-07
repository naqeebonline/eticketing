@extends('layouts.admin')
@section('title', 'Edit Schedule')
@section('header', 'Edit Schedule')
@section('breadcrumb', $schedule->departure_date->format('M d, Y'))

@section('content')
@php
    $fixedRoute = [[
        'id' => $schedule->route_id,
        'bus_stand_id' => $schedule->route->bus_stand_id,
        'base_fare' => (float) $schedule->route->base_fare,
        'duration_minutes' => $schedule->route->duration_minutes,
    ]];
@endphp
<div
    class="admin-form-shell max-w-2xl"
    x-data="scheduleForm({
        routes: @js($fixedRoute),
        vehicles: @js($vehicles),
        drivers: @js($drivers),
        routeId: @js((string) $schedule->route_id),
        vehicleId: @js(old('vehicle_id', $schedule->vehicle_id)),
        driverId: @js(old('driver_id', $schedule->driver_id)),
        fare: @js(old('fare', $schedule->fare)),
        departureDate: @js(old('departure_date', $schedule->departure_date->format('Y-m-d'))),
        departureTime: @js(old('departure_time', \Carbon\Carbon::parse($schedule->departure_time)->format('H:i'))),
        arrivalTime: @js(old('arrival_time', $schedule->arrival_time ? \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') : '')),
    })"
>
    <x-ui.page-header
        title="Edit schedule"
        subtitle="Vehicle & driver — sirf is stand ki fleet (route fixed)"
    />

    <div class="admin-panel mb-6 px-5 py-4">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Route (fixed)</p>
        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
            {{ $schedule->route->displayLabel() }}
        </p>
        @if($schedule->route->busStand)
        <p class="mt-1 text-sm text-slate-500">Bus stand: {{ $schedule->route->busStand->displayTitle() }}</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.form-section
            title="Bus & driver (this trip only)"
            description="Sirf is route ke bus stand ki vehicles aur drivers."
            icon="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
        >
            <div>
                <label for="vehicle_id" class="form-label">Vehicle</label>
                <select
                    id="vehicle_id"
                    name="vehicle_id"
                    required
                    class="input-field"
                    x-model="vehicleId"
                    @change="onVehicleChange($event.target.value)"
                >
                    <template x-for="vehicle in filteredVehicles" :key="vehicle.id">
                        <option :value="vehicle.id" x-text="vehicle.label"></option>
                    </template>
                </select>
                @error('vehicle_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="driver_id" class="form-label">Driver for this schedule</label>
                <select id="driver_id" name="driver_id" class="input-field" x-model="driverId">
                    <option value="">— No driver —</option>
                    <template x-for="driver in filteredDrivers" :key="driver.id">
                        <option :value="driver.id" x-text="driver.label"></option>
                    </template>
                </select>
                <p class="form-hint">Vehicle change karein to default driver auto-select ho jata hai.</p>
                @error('driver_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </x-ui.form-section>

        <x-ui.form-section title="Departure" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="departure_date" class="form-label">Departure date</label>
                    <input id="departure_date" type="date" name="departure_date" required class="input-field" x-model="departureDate">
                </div>
                <div>
                    <label for="departure_time" class="form-label">Departure time</label>
                    <input id="departure_time" type="time" name="departure_time" required class="input-field" x-model="departureTime" @change="onDepartureTimeChange()">
                    @error('departure_time')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="arrival_time" class="form-label">Arrival time</label>
                    <input id="arrival_time" type="time" name="arrival_time" class="input-field" x-model="arrivalTime">
                </div>
                <div>
                    <label for="fare" class="form-label">Fare (PKR)</label>
                    <input id="fare" type="number" name="fare" step="0.01" required min="0" class="input-field" x-model="fare">
                    @error('fare')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <x-ui.select label="Status" name="status" required class="sm:col-span-2">
                    @foreach(['scheduled', 'boarding', 'departed', 'completed', 'cancelled'] as $st)
                    <option value="{{ $st }}" @selected(old('status', $schedule->status) === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.textarea label="Notes" name="notes" rows="2" class="sm:col-span-2">{{ old('notes', $schedule->notes) }}</x-ui.textarea>
            </div>
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.button href="{{ route('admin.schedules.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
</div>
@endsection
