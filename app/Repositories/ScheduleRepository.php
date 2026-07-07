<?php

namespace App\Repositories;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function findByUuid(string $uuid): ?Schedule
    {
        return Schedule::with(['route.stops', 'vehicle.seatMaps.seats', 'driver.user'])
            ->where('uuid', $uuid)
            ->first();
    }

    public function searchAvailable(string $from, string $to, string $date): Collection
    {
        return Schedule::with(['route.busStand', 'vehicle'])
            ->whereHas('route', function ($q) use ($from, $to) {
                $q->where('departure_city', 'like', "%{$from}%")
                    ->where('destination_city', 'like', "%{$to}%")
                    ->where('is_active', true);
            })
            ->where('departure_date', $date)
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_time')
            ->get();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Schedule::with(['route', 'vehicle', 'driver.user']);

        if (empty($filters['date']) && empty($filters['include_past'])) {
            $query->where('departure_date', '>=', now()->toDateString());
        }

        $query->orderBy('departure_date')->orderBy('departure_time');

        if (! empty($filters['route_id'])) {
            $query->where('route_id', $filters['route_id']);
        }
        if (! empty($filters['date'])) {
            $query->where('departure_date', $filters['date']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['bus_stand_ids'])) {
            $query->whereHas('route', fn ($q) => $q->whereIn('bus_stand_id', $filters['bus_stand_ids']));
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function hasConflict(int $vehicleId, string $date, string $departureTime, ?int $excludeId = null): bool
    {
        $query = Schedule::where('vehicle_id', $vehicleId)
            ->where('departure_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where('departure_time', $departureTime);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
