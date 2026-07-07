<?php

namespace App\Services\Schedule;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use App\Models\WeeklySchedulePlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WeeklyScheduleService
{
    public const HORIZON_DAYS = 365;

    public function __construct(
        private ScheduleAssignmentService $assignmentService,
        private ScheduleRepositoryInterface $scheduleRepository,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $weekdays  keys 1–7
     */
    public function savePlan(User $user, array $data, array $weekdays, ?WeeklySchedulePlan $existing = null): WeeklySchedulePlan
    {
        $route = Route::findOrFail($data['route_id']);
        abort_unless($user->ownsBusStand($route->bus_stand_id), 403);

        $vehicle = $this->assignmentService->resolveVehicleForStand($data['vehicle_id'], $route->bus_stand_id);
        $driver = $this->assignmentService->resolveDriverForStand($data['driver_id'] ?? null, $route->bus_stand_id);
        $this->assignmentService->assertScheduleResourcesMatch($route, $vehicle, $driver);

        $activeDays = $this->normalizeWeekdays($weekdays);

        if ($activeDays === []) {
            throw ValidationException::withMessages([
                'weekdays' => ['Kam az kam ek weekday par departure time set karein.'],
            ]);
        }

        $driverId = $data['driver_id'] ?? null;
        if (empty($driverId) && $vehicle->driver_id) {
            $driverId = $vehicle->driver_id;
        }

        return DB::transaction(function () use ($data, $activeDays, $driverId, $existing, $route, $vehicle) {
            $plan = $existing ?? WeeklySchedulePlan::query()
                ->where('route_id', $route->id)
                ->where('vehicle_id', $vehicle->id)
                ->first();

            if ($plan) {
                $plan->update([
                    'driver_id' => $driverId,
                    'fare' => $data['fare'],
                    'is_active' => true,
                ]);
            } else {
                $plan = WeeklySchedulePlan::create([
                    'route_id' => $route->id,
                    'vehicle_id' => $vehicle->id,
                    'driver_id' => $driverId,
                    'fare' => $data['fare'],
                    'is_active' => true,
                ]);
            }

            $plan->days()->delete();

            foreach ($activeDays as $day) {
                $plan->days()->create($day);
            }

            $this->materializePlan($plan);

            return $plan->fresh(['days', 'route', 'vehicle', 'driver']);
        });
    }

    public function materializePlan(WeeklySchedulePlan $plan): int
    {
        $plan->load(['days', 'vehicle', 'route']);

        $start = today();
        $end = today()->addDays(self::HORIZON_DAYS);
        $created = 0;

        $this->removeFutureUnbookedSchedules($plan);

        $current = $start->copy();

        while ($current->lte($end)) {
            $dayConfig = $plan->days->firstWhere('day_of_week', $current->dayOfWeekIso);

            if ($dayConfig) {
                $created += $this->upsertScheduleForDate($plan, $current, $dayConfig) ? 1 : 0;
            }

            $current->addDay();
        }

        return $created;
    }

    private function removeFutureUnbookedSchedules(WeeklySchedulePlan $plan): void
    {
        Schedule::query()
            ->where('weekly_schedule_plan_id', $plan->id)
            ->where('departure_date', '>=', today())
            ->where('status', 'scheduled')
            ->whereDoesntHave('bookings', fn ($q) => $q->whereNotIn('status', ['cancelled', 'refunded']))
            ->delete();
    }

    private function upsertScheduleForDate(WeeklySchedulePlan $plan, Carbon $date, $dayConfig): bool
    {
        $departureTime = Carbon::parse($dayConfig->departure_time)->format('H:i:s');
        $dateStr = $date->toDateString();

        $existingForPlan = Schedule::query()
            ->where('weekly_schedule_plan_id', $plan->id)
            ->where('departure_date', $dateStr)
            ->first();

        if ($this->scheduleRepository->hasConflict(
            $plan->vehicle_id,
            $dateStr,
            $departureTime,
            $existingForPlan?->id
        )) {
            return false;
        }

        $arrivalTime = $dayConfig->arrival_time
            ? Carbon::parse($dayConfig->arrival_time)->format('H:i:s')
            : null;

        Schedule::updateOrCreate(
            [
                'weekly_schedule_plan_id' => $plan->id,
                'departure_date' => $dateStr,
            ],
            [
                'route_id' => $plan->route_id,
                'vehicle_id' => $plan->vehicle_id,
                'driver_id' => $plan->driver_id,
                'departure_time' => $departureTime,
                'arrival_time' => $arrivalTime,
                'fare' => $plan->fare,
                'available_seats' => $plan->vehicle->total_seats,
                'status' => 'scheduled',
            ]
        );

        return true;
    }

    /**
     * @param  array<int, array<string, mixed>>  $weekdays
     * @return list<array{day_of_week: int, departure_time: string, arrival_time: string|null}>
     */
    private function normalizeWeekdays(array $weekdays): array
    {
        $active = [];

        foreach ($weekdays as $dayOfWeek => $row) {
            $time = trim((string) ($row['departure_time'] ?? ''));

            if ($time === '') {
                continue;
            }

            $dow = (int) $dayOfWeek;
            if ($dow < 1 || $dow > 7) {
                continue;
            }

            $active[] = [
                'day_of_week' => $dow,
                'departure_time' => Carbon::parse($time)->format('H:i:s'),
                'arrival_time' => ! empty($row['arrival_time'])
                    ? Carbon::parse($row['arrival_time'])->format('H:i:s')
                    : null,
            ];
        }

        return $active;
    }

    public function queryForUser(User $user)
    {
        $query = WeeklySchedulePlan::query()
            ->with(['route.busStand', 'vehicle', 'driver.user', 'days'])
            ->where('is_active', true)
            ->latest();

        $standIds = $user->manageableBusStandIds();

        if ($standIds !== null) {
            $query->whereHas('route', fn ($q) => $q->whereIn('bus_stand_id', $standIds));
        }

        return $query;
    }

    public function authorizePlan(User $user, WeeklySchedulePlan $plan): void
    {
        $plan->loadMissing('route');

        abort_unless(
            $user->isSuperAdmin() || $user->ownsBusStand($plan->route->bus_stand_id),
            403
        );
    }

    /** @return array<int, array{departure_time: string, arrival_time: string}> */
    public function weekdaysForForm(WeeklySchedulePlan $plan): array
    {
        $result = [];

        foreach (WeeklySchedulePlan::weekdayLabels() as $dow => $label) {
            $result[$dow] = ['departure_time' => '', 'arrival_time' => ''];
        }

        foreach ($plan->days as $day) {
            $result[$day->day_of_week] = [
                'departure_time' => Carbon::parse($day->departure_time)->format('H:i'),
                'arrival_time' => $day->arrival_time
                    ? Carbon::parse($day->arrival_time)->format('H:i')
                    : '',
            ];
        }

        return $result;
    }
}
