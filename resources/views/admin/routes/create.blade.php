@extends('layouts.admin')
@section('title', 'Add Route')
@section('header', 'Add Route')
@section('breadcrumb', 'Linked to one bus stand')

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header title="New route" subtitle="Route name departure aur destination city se auto banega" />

    @if($busStands->isEmpty())
    <div class="admin-panel p-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">Pehle bus stand register karein.</p>
        <x-ui.button href="{{ route('admin.bus-stands.create') }}" class="mt-4">Add bus stand</x-ui.button>
    </div>
    @else
    <div
        x-data="routeForm({
            departureCity: @js(old('departure_city', $defaultDepartureCity ?? '')),
            destinationCity: @js(old('destination_city', '')),
        })"
    >
        <form method="POST" action="{{ route('admin.routes.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="name" :value="routeName" :disabled="!routeName">

            <x-ui.form-section title="Bus stand" description="Yeh route sirf is stand ki schedules mein available hoga." icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5">
                @if($busStands->count() === 1)
                <input type="hidden" name="bus_stand_id" value="{{ $busStands->first()->id }}">
                <div class="rounded-xl border border-primary-200 bg-primary-50/80 px-4 py-3 dark:border-primary-800/50 dark:bg-primary-950/40">
                    <p class="text-xs font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400">Bus stand</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $busStands->first()->displayTitle() }}</p>
                </div>
                @else
                <x-ui.select label="Bus stand" name="bus_stand_id" required>
                    <option value="">— Select stand —</option>
                    @foreach($busStands as $stand)
                    <option value="{{ $stand->id }}" @selected((int) old('bus_stand_id', $selectedStandId) === $stand->id)>
                        {{ $stand->displayTitle() }}@if($stand->terminal) — {{ $stand->terminal->name }}@endif
                    </option>
                    @endforeach
                </x-ui.select>
                @endif
            </x-ui.form-section>

            <x-ui.form-section title="Route details" icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.city-select label="Departure city" name="departure_city" :value="old('departure_city', $defaultDepartureCity)" required />
                    <x-ui.city-select label="Destination city" name="destination_city" :value="old('destination_city')" required />
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Route name (auto)</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white" x-text="routeName || '—'"></p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Distance (km)" name="distance_km" type="number" step="0.01" :value="old('distance_km')" />
                    <x-ui.input label="Duration (minutes)" name="duration_minutes" type="number" :value="old('duration_minutes')" />
                    <x-ui.input label="Base fare (PKR)" name="base_fare" type="number" step="0.01" required min="0" class="sm:col-span-2" :value="old('base_fare')" />
                </div>
            </x-ui.form-section>

            <div class="flex gap-3">
                <x-ui.button type="submit" ::disabled="!routeName">Create route</x-ui.button>
                <x-ui.button href="{{ route('admin.routes.index', request()->only('bus_stand_id')) }}" variant="secondary">Cancel</x-ui.button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
