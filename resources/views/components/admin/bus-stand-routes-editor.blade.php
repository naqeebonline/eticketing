@props(['busStand', 'routes'])

<x-ui.form-section
    title="Routes"
    description="Har route is stand ke liye — cities change karein to naam auto update hoga."
    icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
>
    @if($routes->isEmpty())
    <p class="text-sm text-slate-500">
        Abhi koi route nahi.
        <a href="{{ route('admin.routes.create', ['bus_stand_id' => $busStand->id]) }}" class="font-semibold text-primary-600 hover:underline">Add route</a>
    </p>
    @else
    <div class="space-y-4">
        @foreach($routes as $route)
        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $route->displayLabel() }}</p>
                <a href="{{ route('admin.routes.edit', $route) }}" class="text-xs font-semibold text-primary-600 hover:underline">Open full edit</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-ui.city-select
                    label="Departure city"
                    :name="'routes['.$route->id.'][departure_city]'"
                    :value="old('routes.'.$route->id.'.departure_city', $route->departure_city)"
                    required
                />
                <x-ui.city-select
                    label="Destination city"
                    :name="'routes['.$route->id.'][destination_city]'"
                    :value="old('routes.'.$route->id.'.destination_city', $route->destination_city)"
                    required
                />
                <x-ui.input
                    label="Base fare (PKR)"
                    :name="'routes['.$route->id.'][base_fare]'"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    :value="old('routes.'.$route->id.'.base_fare', $route->base_fare)"
                />
                <x-ui.input
                    label="Distance (km)"
                    :name="'routes['.$route->id.'][distance_km]'"
                    type="number"
                    step="0.01"
                    :value="old('routes.'.$route->id.'.distance_km', $route->distance_km)"
                />
                <x-ui.input
                    label="Duration (min)"
                    :name="'routes['.$route->id.'][duration_minutes]'"
                    type="number"
                    :value="old('routes.'.$route->id.'.duration_minutes', $route->duration_minutes)"
                />
                <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 self-end dark:border-slate-700 dark:bg-slate-800/50">
                    <input type="hidden" name="routes[{{ $route->id }}][is_active]" value="0">
                    <input
                        type="checkbox"
                        name="routes[{{ $route->id }}][is_active]"
                        value="1"
                        @checked(old('routes.'.$route->id.'.is_active', $route->is_active))
                        class="rounded border-slate-300 text-primary-600"
                    >
                    <span class="text-sm font-medium">Active</span>
                </label>
            </div>
        </div>
        @endforeach
    </div>
  <p class="mt-3 text-xs text-slate-500">
        Naya route:
        <a href="{{ route('admin.routes.create', ['bus_stand_id' => $busStand->id]) }}" class="font-semibold text-primary-600 hover:underline">Add route</a>
    </p>
    @endif
</x-ui.form-section>
