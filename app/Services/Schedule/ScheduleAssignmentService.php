<?php

namespace App\Services\Schedule;

use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\Route\RouteService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ScheduleAssignmentService
{
    public function __construct(private RouteService $routeService) {}

    /**
     * Options for schedule forms — each route/vehicle/driver carries bus_stand_id for client filtering.
     *
     * @return array{
     *     routes: Collection<int, array<string, mixed>>,
     *     vehicles: Collection<int, array<string, mixed>>,
     *     drivers: Collection<int, array<string, mixed>>,
     *     busStands: Collection<int, array<string, mixed>>,
     * }
     */
    public function formOptionsFor(User $user, ?int $busStandId = null): array
    {
        $routesQuery = Route::query()->where('is_active', true)->with('busStand');
        $vehiclesQuery = Vehicle::query()->where('is_active', true)->with('driver');
        $driversQuery = Driver::query()->where('is_active', true);

        $standIds = $this->resolveStandIds($user, $busStandId);

        if ($standIds !== null) {
            $routesQuery->whereIn('bus_stand_id', $standIds);
            $vehiclesQuery->whereIn('bus_stand_id', $standIds);
            $driversQuery->whereIn('bus_stand_id', $standIds);
        }

        $routes = $routesQuery->orderBy('name')->get();
        $vehicles = $vehiclesQuery->orderBy('name')->get();
        $drivers = $driversQuery->orderBy('name')->get();

        $busStands = $routes
            ->pluck('busStand')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn ($stand) => [
                'id' => $stand->id,
                'name' => $stand->displayTitle(),
                'terminal' => $stand->terminal?->name,
            ]);

        return [
            'routes' => $routes->map(fn (Route $route) => [
                'id' => $route->id,
                'bus_stand_id' => $route->bus_stand_id,
                'label' => $route->displayLabel(),
                'base_fare' => (float) $route->base_fare,
                'duration_minutes' => $route->duration_minutes,
            ]),
            'vehicles' => $vehicles->map(fn (Vehicle $vehicle) => [
                'id' => $vehicle->id,
                'bus_stand_id' => $vehicle->bus_stand_id,
                'driver_id' => $vehicle->driver_id,
                'label' => trim("{$vehicle->name} — {$vehicle->bus_number} ({$vehicle->total_seats} seats)"),
            ]),
            'drivers' => $drivers->map(fn (Driver $driver) => [
                'id' => $driver->id,
                'bus_stand_id' => $driver->bus_stand_id,
                'label' => $driver->displayName(),
            ]),
            'busStands' => $busStands,
        ];
    }

    public function resolveVehicleForStand(int $vehicleId, int $busStandId): Vehicle
    {
        return Vehicle::query()
            ->where('id', $vehicleId)
            ->where('bus_stand_id', $busStandId)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function resolveDriverForStand(?int $driverId, int $busStandId): ?Driver
    {
        if (! $driverId) {
            return null;
        }

        return Driver::query()
            ->where('id', $driverId)
            ->where('bus_stand_id', $busStandId)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function assertScheduleResourcesMatch(Route $route, Vehicle $vehicle, ?Driver $driver): void
    {
        if ($vehicle->bus_stand_id !== $route->bus_stand_id) {
            throw ValidationException::withMessages([
                'vehicle_id' => ['Vehicle must belong to the same bus stand as the route.'],
            ]);
        }

        if ($driver && $driver->bus_stand_id !== $route->bus_stand_id) {
            throw ValidationException::withMessages([
                'driver_id' => ['Driver must belong to the same bus stand as the route.'],
            ]);
        }
    }

    /**
     * Ensure the plan's current vehicle/driver appear in edit-form options (e.g. if marked inactive).
     *
     * @param  array{routes: Collection, vehicles: Collection, drivers: Collection, busStands: Collection}  $options
     * @return array{routes: Collection, vehicles: Collection, drivers: Collection, busStands: Collection}
     */
    public function withAssignedPlanResources(array $options, \App\Models\WeeklySchedulePlan $plan): array
    {
        $plan->loadMissing(['vehicle', 'driver']);

        $vehicles = $options['vehicles'];
        if ($plan->vehicle && ! $vehicles->contains(fn (array $v) => (int) $v['id'] === (int) $plan->vehicle_id)) {
            $vehicles = $vehicles->push([
                'id' => $plan->vehicle->id,
                'bus_stand_id' => $plan->vehicle->bus_stand_id,
                'driver_id' => $plan->vehicle->driver_id,
                'label' => trim("{$plan->vehicle->name} — {$plan->vehicle->bus_number} ({$plan->vehicle->total_seats} seats)"),
            ])->values();
        }

        $drivers = $options['drivers'];
        $driverId = $plan->driver_id ?? $plan->vehicle?->driver_id;
        if ($driverId && ! $drivers->contains(fn (array $d) => (int) $d['id'] === (int) $driverId)) {
            $driver = $plan->driver ?? Driver::find($driverId);
            if ($driver) {
                $drivers = $drivers->push([
                    'id' => $driver->id,
                    'bus_stand_id' => $driver->bus_stand_id,
                    'label' => $driver->displayName(),
                ])->values();
            }
        }

        return [
            ...$options,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
        ];
    }

    /** @return list<int>|null */
    private function resolveStandIds(User $user, ?int $busStandId): ?array
    {
        if ($busStandId !== null) {
            abort_unless($user->ownsBusStand($busStandId), 403);

            return [$busStandId];
        }

        if ($user->isSuperAdmin()) {
            return null;
        }

        return $user->manageableBusStandIds();
    }
}
