<?php

namespace App\Contracts\Repositories;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface
{
    public function findByUuid(string $uuid): ?Schedule;

    public function searchAvailable(string $from, string $to, string $date): Collection;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Schedule;

    public function hasConflict(int $vehicleId, string $date, string $departureTime, ?int $excludeId = null): bool;
}
