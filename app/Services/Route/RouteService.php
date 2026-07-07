<?php

namespace App\Services\Route;

use App\Models\BusStand;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class RouteService
{
    public function buildRouteName(string $departureCity, string $destinationCity): string
    {
        return trim("{$departureCity} → {$destinationCity}");
    }

    public function queryForUser(User $user): Builder
    {
        $query = Route::query()->with(['busStand.terminal'])->latest();

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isTerminalAdmin()) {
            $terminalIds = $user->ownedTerminals()->pluck('id');

            return $query->whereHas(
                'busStand',
                fn (Builder $q) => $q->whereIn('terminal_id', $terminalIds)
            );
        }

        $standIds = $user->manageableBusStandIds();

        if ($standIds === null) {
            return $query;
        }

        if ($standIds === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('bus_stand_id', $standIds);
    }

    public function activeRoutesForStand(int $busStandId): Builder
    {
        return Route::query()
            ->where('bus_stand_id', $busStandId)
            ->where('is_active', true)
            ->orderBy('name');
    }

    public function resolveForStand(int $routeId, int $busStandId): Route
    {
        return Route::query()
            ->where('id', $routeId)
            ->where('bus_stand_id', $busStandId)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function assertStandAccess(User $user, int $busStandId): void
    {
        abort_unless($user->ownsBusStand($busStandId), 403);
    }

    public function assertSameStand(Route $route, int $busStandId): void
    {
        if ($route->bus_stand_id !== $busStandId) {
            throw ValidationException::withMessages([
                'route_id' => ['This route does not belong to the selected bus stand.'],
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $routesInput
     */
    public function createManyForStand(BusStand $stand, array $routesInput): int
    {
        $created = 0;

        foreach ($routesInput as $data) {
            $destination = trim((string) ($data['destination_city'] ?? ''));

            if ($destination === '') {
                continue;
            }

            $departure = trim((string) ($data['departure_city'] ?? $stand->city));

            if ($departure === '' || $departure === $destination) {
                throw ValidationException::withMessages([
                    'routes' => ['Each route needs different departure and destination cities.'],
                ]);
            }

            $this->createForStand($stand, [
                'departure_city' => $departure,
                'destination_city' => $destination,
                'name' => $this->buildRouteName($departure, $destination),
                'distance_km' => $data['distance_km'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
                'base_fare' => $data['base_fare'] ?? 0,
            ]);

            $created++;
        }

        return $created;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createForStand(BusStand $stand, array $data): Route
    {
        return Route::create([
            'bus_stand_id' => $stand->id,
            'departure_city' => $data['departure_city'],
            'destination_city' => $data['destination_city'],
            'name' => $data['name'],
            'distance_km' => $data['distance_km'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'base_fare' => $data['base_fare'],
            'is_active' => true,
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $routesInput
     */
    public function syncRoutesForStand(BusStand $stand, array $routesInput): void
    {
        if ($routesInput === []) {
            return;
        }

        $standRouteIds = $stand->routes()->pluck('id')->map(fn ($id) => (int) $id)->all();

        foreach ($routesInput as $routeId => $data) {
            $routeId = (int) $routeId;

            if (! in_array($routeId, $standRouteIds, true)) {
                continue;
            }

            $departure = trim((string) ($data['departure_city'] ?? ''));
            $destination = trim((string) ($data['destination_city'] ?? ''));

            if ($departure === '' || $destination === '') {
                continue;
            }

            if ($departure === $destination) {
                throw ValidationException::withMessages([
                    "routes.{$routeId}.destination_city" => ['Destination must be different from departure.'],
                ]);
            }

            $stand->routes()->whereKey($routeId)->update([
                'departure_city' => $departure,
                'destination_city' => $destination,
                'name' => $this->buildRouteName($departure, $destination),
                'distance_km' => $data['distance_km'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
                'base_fare' => $data['base_fare'],
                'is_active' => filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRoute(Route $route, array $data): Route
    {
        $departure = $data['departure_city'];
        $destination = $data['destination_city'];

        if ($departure === $destination) {
            throw ValidationException::withMessages([
                'destination_city' => ['Destination must be different from departure.'],
            ]);
        }

        $route->update([
            'departure_city' => $departure,
            'destination_city' => $destination,
            'name' => $this->buildRouteName($departure, $destination),
            'distance_km' => $data['distance_km'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'base_fare' => $data['base_fare'],
            'is_active' => filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ]);

        return $route->fresh();
    }

    public function selectableStandsFor(User $user): Builder
    {
        $query = BusStand::query()
            ->with('terminal')
            ->where('is_active', true)
            ->orderBy('name');

        $standIds = $user->manageableBusStandIds();

        if ($standIds !== null) {
            if ($standIds === []) {
                return $query->whereRaw('1 = 0');
            }

            $query->whereIn('id', $standIds);
        }

        if ($user->isTerminalAdmin()) {
            $query->whereIn('terminal_id', $user->ownedTerminals()->pluck('id'));
        }

        return $query;
    }
}
