@props(['cities'])

<x-ui.form-section
    title="Routes"
    description="Departure terminal city se auto set hoti hai. Destination city aur fare yahan add karein — optional, baad mein bhi add kar sakte hain."
    icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
>
    @error('routes')
    <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div class="space-y-4">
        <template x-for="(route, index) in routes" :key="index">
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                <div class="mb-3 flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        Route <span x-text="index + 1"></span>
                        <span x-show="fromCity && route.destination_city" class="font-normal text-slate-500">
                            · <span x-text="fromCity + ' → ' + route.destination_city"></span>
                        </span>
                    </p>
                    <button
                        type="button"
                        class="text-xs font-semibold text-red-600 hover:underline disabled:opacity-40"
                        x-show="routes.length > 1"
                        @click="removeRoute(index)"
                    >
                        Remove
                    </button>
                </div>

                <input type="hidden" :name="'routes[' + index + '][departure_city]'" :value="fromCity">

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Departure city</label>
                        <input
                            type="text"
                            class="input-field bg-slate-50 dark:bg-slate-800/50"
                            :value="fromCity || '— Select terminal first —'"
                            readonly
                            tabindex="-1"
                        >
                    </div>

                    <div>
                        <label class="form-label" :for="'route_destination_' + index">Destination city</label>
                        <select
                            :id="'route_destination_' + index"
                            class="input-field"
                            :name="'routes[' + index + '][destination_city]'"
                            x-model="route.destination_city"
                        >
                            <option value="">— Select city —</option>
                            <template x-for="city in cities" :key="city">
                                <option
                                    :value="city"
                                    :disabled="city === fromCity"
                                    x-text="city"
                                ></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" :for="'route_fare_' + index">Base fare (PKR)</label>
                        <input
                            :id="'route_fare_' + index"
                            type="number"
                            step="0.01"
                            min="0"
                            class="input-field"
                            :name="'routes[' + index + '][base_fare]'"
                            x-model="route.base_fare"
                            placeholder="e.g. 2500"
                        >
                    </div>

                    <div>
                        <label class="form-label" :for="'route_distance_' + index">Distance (km)</label>
                        <input
                            :id="'route_distance_' + index"
                            type="number"
                            step="0.01"
                            min="0"
                            class="input-field"
                            :name="'routes[' + index + '][distance_km]'"
                            x-model="route.distance_km"
                            placeholder="Optional"
                        >
                    </div>

                    <div>
                        <label class="form-label" :for="'route_duration_' + index">Duration (min)</label>
                        <input
                            :id="'route_duration_' + index"
                            type="number"
                            min="0"
                            class="input-field"
                            :name="'routes[' + index + '][duration_minutes]'"
                            x-model="route.duration_minutes"
                            placeholder="Optional"
                        >
                    </div>
                </div>
            </div>
        </template>
    </div>

    <button
        type="button"
        class="mt-4 text-sm font-semibold text-primary-600 hover:underline"
        @click="addRoute()"
    >
        + Add another route
    </button>
</x-ui.form-section>
