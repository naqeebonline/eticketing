@extends('layouts.admin')
@section('title', 'Edit Route')
@section('header', 'Edit Route')
@section('breadcrumb', $route->displayLabel())

@section('content')
<div class="admin-form-shell max-w-2xl">
    <x-ui.page-header
        title="Edit route"
        :subtitle="$route->busStand?->displayTitle()"
    />

    <div class="mb-4">
        <a href="{{ route('admin.bus-stands.edit', $route->busStand) }}" class="text-sm font-semibold text-primary-600 hover:underline">← Back to bus stand</a>
    </div>

    <div
        x-data="routeForm({
            departureCity: @js(old('departure_city', $route->departure_city)),
            destinationCity: @js(old('destination_city', $route->destination_city)),
        })"
    >
        <form method="POST" action="{{ route('admin.routes.update', $route) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" :value="routeName" :disabled="!routeName">

            <x-ui.form-section title="Route details" icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.city-select label="Departure city" name="departure_city" :value="old('departure_city', $route->departure_city)" required />
                    <x-ui.city-select label="Destination city" name="destination_city" :value="old('destination_city', $route->destination_city)" required />
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Route name (auto)</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white" x-text="routeName || '—'"></p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.input label="Distance (km)" name="distance_km" type="number" step="0.01" :value="old('distance_km', $route->distance_km)" />
                    <x-ui.input label="Duration (minutes)" name="duration_minutes" type="number" :value="old('duration_minutes', $route->duration_minutes)" />
                    <x-ui.input label="Base fare (PKR)" name="base_fare" type="number" step="0.01" required min="0" :value="old('base_fare', $route->base_fare)" />
                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 self-end dark:border-slate-700 dark:bg-slate-800/50">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $route->is_active)) class="rounded border-slate-300 text-primary-600">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </x-ui.form-section>

            <div class="flex gap-3">
                <x-ui.button type="submit" ::disabled="!routeName">Save route</x-ui.button>
                <x-ui.button href="{{ route('admin.bus-stands.edit', $route->busStand) }}" variant="secondary">Cancel</x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection
