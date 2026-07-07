@php
    $weekdayLabels = \App\Models\WeeklySchedulePlan::weekdayLabels();
    $defaultWeekdays = [];
    foreach ($weekdayLabels as $dow => $label) {
        $defaultWeekdays[$dow] = ['departure_time' => '', 'arrival_time' => ''];
    }
    $initialWeekdays = $initialWeekdays ?? $defaultWeekdays;
    if (old('weekdays')) {
        foreach (old('weekdays') as $dow => $row) {
            $initialWeekdays[(int) $dow] = [
                'departure_time' => $row['departure_time'] ?? '',
                'arrival_time' => $row['arrival_time'] ?? '',
            ];
        }
    }
    $planRouteId = isset($plan) ? $plan->route_id : '';
    $planVehicleId = isset($plan) ? $plan->vehicle_id : '';
    $planDriverId = isset($plan) ? ($plan->driver_id ?? $plan->vehicle?->driver_id ?? '') : '';
    $planFare = isset($plan) ? $plan->fare : '';
    $planStandId = isset($plan) ? $plan->route->bus_stand_id : null;
@endphp

<div
    class="admin-form-shell max-w-2xl"
    x-data="scheduleForm({
        routes: @js($routes),
        vehicles: @js($vehicles),
        drivers: @js($drivers),
        routeId: @js(old('route_id', $planRouteId)),
        vehicleId: @js(old('vehicle_id', $planVehicleId)),
        driverId: @js(old('driver_id', $planDriverId)),
        standId: @js($planStandId),
        fare: @js(old('fare', $planFare)),
        initialWeekdays: @js($initialWeekdays),
    })"
>
    <x-ui.page-header
        :title="$formTitle ?? 'Weekly schedule'"
        :subtitle="$formSubtitle ?? 'Ek baar set karein — har hafte inhi dinon aur times par bus chalegi (permanent).'"
    />

    @if($routes->isEmpty())
    <div class="admin-panel p-6">
        <p class="text-sm text-slate-600 dark:text-slate-400">Pehle apne bus stand ke liye route add karein.</p>
        <x-ui.button href="{{ route('admin.routes.create') }}" class="mt-4">Add route</x-ui.button>
    </div>
    @else
    <form method="POST" action="{{ $formAction }}" class="space-y-6">
        @csrf
        @if($formMethod ?? false)
            @method($formMethod)
        @endif

        <x-ui.form-section title="Route & fare" icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
            <div>
                <label for="route_id" class="form-label">Route</label>
                <select id="route_id" name="route_id" required class="input-field" x-model="routeId" @change="onRouteChange($event.target.value)">
                    <option value="">— Select route —</option>
                    @foreach($routes as $route)
                    <option value="{{ $route['id'] }}" @selected((string) old('route_id', $planRouteId) === (string) $route['id'])>{{ $route['label'] }}</option>
                    @endforeach
                </select>
                @error('route_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="fare" class="form-label">Fare (PKR)</label>
                <input id="fare" type="number" name="fare" step="0.01" required min="0" class="input-field" x-model="fare">
                @error('fare')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </x-ui.form-section>

        <x-ui.form-section title="Bus & driver" icon="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4">
            <div>
                <label for="vehicle_id" class="form-label">Vehicle</label>
                <select id="vehicle_id" name="vehicle_id" required class="input-field" x-model="vehicleId" @change="onVehicleChange($event.target.value)" :disabled="!standId">
                    <option value="" x-text="standId ? '— Select vehicle —' : '— Pehle route select karein —'"></option>
                    <template x-for="vehicle in filteredVehicles" :key="vehicle.id">
                        <option :value="String(vehicle.id)" x-text="vehicle.label" :selected="String(vehicle.id) === String(vehicleId)"></option>
                    </template>
                </select>
                @error('vehicle_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="driver_id" class="form-label">Driver</label>
                <select id="driver_id" name="driver_id" class="input-field" x-model="driverId" :disabled="!standId">
                    <option value="">— Vehicle default —</option>
                    <template x-for="driver in filteredDrivers" :key="driver.id">
                        <option :value="String(driver.id)" x-text="driver.label" :selected="String(driver.id) === String(driverId)"></option>
                    </template>
                </select>
            </div>
        </x-ui.form-section>

        <x-ui.form-section
            title="Weekly timetable"
            description="Har weekday ke saamne time daalein. Jis din bus nahi chalti — khali chhor dein (3, 4 ya 7 din — aap decide karein)."
            icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
        >
            @error('weekdays')<p class="mb-4 form-error">{{ $message }}</p>@enderror

            <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800/80">
                        <tr>
                            <th class="px-4 py-3">Day</th>
                            <th class="px-4 py-3">Departure</th>
                            <th class="px-4 py-3">Arrival</th>
                            <th class="px-4 py-3 w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <template x-for="day in weekdayRows" :key="day.value">
                            <tr :class="weekdays[day.value]?.departure_time ? 'bg-white dark:bg-slate-900' : 'bg-slate-50/50 dark:bg-slate-900/40'">
                                <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200" x-text="day.label"></td>
                                <td class="px-4 py-3">
                                    <input
                                        type="time"
                                        class="input-field"
                                        :name="'weekdays[' + day.value + '][departure_time]'"
                                        x-model="weekdays[day.value].departure_time"
                                        @change="onDayTimeChange(day.value)"
                                    >
                                </td>
                                <td class="px-4 py-3">
                                    <input
                                        type="time"
                                        class="input-field"
                                        :name="'weekdays[' + day.value + '][arrival_time]'"
                                        x-model="weekdays[day.value].arrival_time"
                                    >
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="text-xs font-semibold text-slate-500 hover:text-red-600" @click="clearDay(day.value)" x-show="weekdays[day.value]?.departure_time">Clear</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 rounded-xl border border-primary-200 bg-primary-50/80 px-4 py-3 dark:border-primary-800/50 dark:bg-primary-950/40">
                <p class="text-xs font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400">Preview</p>
                <p class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-200" x-text="schedulePreview"></p>
            </div>
        </x-ui.form-section>

        <div class="flex gap-3">
            <x-ui.button type="submit">{{ $submitLabel ?? 'Save weekly schedule' }}</x-ui.button>
            <x-ui.button href="{{ route('admin.schedules.index') }}" variant="secondary">Cancel</x-ui.button>
        </div>
    </form>
    @endif
</div>
