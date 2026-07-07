<?php

namespace App\Services\Schedule;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ScheduleCreationService
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository,
        private ScheduleAssignmentService $assignmentService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $user, array $data): Schedule
    {
        $route = Route::findOrFail($data['route_id']);
        abort_unless($user->ownsBusStand($route->bus_stand_id), 403);

        $vehicle = $this->assignmentService->resolveVehicleForStand($data['vehicle_id'], $route->bus_stand_id);
        $driver = $this->assignmentService->resolveDriverForStand($data['driver_id'] ?? null, $route->bus_stand_id);
        $this->assignmentService->assertScheduleResourcesMatch($route, $vehicle, $driver);

        if ($this->scheduleRepository->hasConflict(
            (int) $data['vehicle_id'],
            $data['departure_date'],
            $data['departure_time']
        )) {
            throw ValidationException::withMessages([
                'departure_time' => 'Vehicle already scheduled at this time.',
            ]);
        }

        $driverId = $data['driver_id'] ?? null;
        if (empty($driverId) && $vehicle->driver_id) {
            $driverId = $vehicle->driver_id;
        }

        return $this->scheduleRepository->create([
            'route_id' => $data['route_id'],
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driverId,
            'departure_date' => $data['departure_date'],
            'departure_time' => $this->normalizeTime($data['departure_time']),
            'arrival_time' => ! empty($data['arrival_time'])
                ? $this->normalizeTime($data['arrival_time'])
                : null,
            'fare' => $data['fare'],
            'available_seats' => $vehicle->total_seats,
            'status' => 'scheduled',
        ]);
    }

    /**
     * @param  array<string, mixed>  $baseData
     * @param  array<int, array<string, mixed>>  $times
     * @return array{created: int, skipped: list<string>}
     */
    public function createDaily(
        User $user,
        array $baseData,
        array $times,
        string $startDate,
        string $endDate,
    ): array {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            throw ValidationException::withMessages([
                'end_date' => ['End date start date se pehle nahi ho sakti.'],
            ]);
        }

        $validTimes = array_values(array_filter($times, fn ($time) => ! empty($time['departure_time'])));

        if ($validTimes === []) {
            throw ValidationException::withMessages([
                'times' => ['Kam az kam ek departure time add karein.'],
            ]);
        }

        $dayCount = (int) $start->diffInDays($end) + 1;
        $totalSlots = $dayCount * count($validTimes);

        if ($totalSlots > 365) {
            throw ValidationException::withMessages([
                'end_date' => ["Maximum 365 schedules allowed — aapke selection se {$totalSlots} banenge. Date range ya times kam karein."],
            ]);
        }

        $trips = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            foreach ($validTimes as $time) {
                $trips[] = [
                    'departure_date' => $current->toDateString(),
                    'departure_time' => $time['departure_time'],
                    'arrival_time' => $time['arrival_time'] ?? null,
                ];
            }

            $current->addDay();
        }

        return $this->createMany($user, $baseData, $trips);
    }

    /**
     * @param  array<string, mixed>  $baseData
     * @param  array<int, array<string, mixed>>  $trips
     * @return array{created: int, skipped: list<string>}
     */
    public function createMany(User $user, array $baseData, array $trips): array
    {
        if (count($trips) > 365) {
            throw ValidationException::withMessages([
                'trips' => ['Ek baar mein zyada se zyada 365 schedules ban sakte hain.'],
            ]);
        }

        $created = 0;
        $skipped = [];

        foreach ($trips as $index => $trip) {
            $departureDate = trim((string) ($trip['departure_date'] ?? ''));
            $departureTime = trim((string) ($trip['departure_time'] ?? ''));

            if ($departureDate === '' || $departureTime === '') {
                continue;
            }

            $payload = [
                ...$baseData,
                'departure_date' => $departureDate,
                'departure_time' => $departureTime,
                'arrival_time' => $trip['arrival_time'] ?? null,
            ];

            try {
                $this->create($user, $payload);
                $created++;
            } catch (ValidationException $e) {
                $label = Carbon::parse($departureDate)->format('M d, Y').' '.$departureTime;
                $skipped[] = $label.' — '.$e->validator->errors()->first();
            }
        }

        if ($created === 0 && $skipped === []) {
            throw ValidationException::withMessages([
                'trips' => ['Kam az kam ek date aur time add karein.'],
            ]);
        }

        return compact('created', 'skipped');
    }

    public function duplicate(User $user, Schedule $source, string $departureDate, string $departureTime): Schedule
    {
        $source->loadMissing('route');

        abort_unless(
            $user->isSuperAdmin() || $user->ownsBusStand($source->route->bus_stand_id),
            403
        );

        return $this->create($user, [
            'route_id' => $source->route_id,
            'vehicle_id' => $source->vehicle_id,
            'driver_id' => $source->driver_id,
            'departure_date' => $departureDate,
            'departure_time' => $departureTime,
            'arrival_time' => $source->arrival_time,
            'fare' => $source->fare,
        ]);
    }

    private function normalizeTime(string $time): string
    {
        return Carbon::parse($time)->format('H:i:s');
    }
}
