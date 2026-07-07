<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\WeeklySchedulePlan;
use App\Services\Schedule\ScheduleAssignmentService;
use App\Services\Schedule\WeeklyScheduleService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository,
        private ScheduleAssignmentService $assignmentService,
        private WeeklyScheduleService $weeklyScheduleService,
    ) {}

    public function index(): View
    {
        $plans = $this->weeklyScheduleService
            ->queryForUser(auth()->user())
            ->paginate(15);

        return view('admin.schedules.index', compact('plans'));
    }

    public function create(): View
    {
        $options = $this->assignmentService->formOptionsFor(auth()->user());

        return view('admin.schedules.create', $options);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'fare' => 'required|numeric|min:0',
            'weekdays' => 'required|array',
            'weekdays.*.departure_time' => 'nullable|date_format:H:i',
            'weekdays.*.arrival_time' => 'nullable|date_format:H:i',
        ]);

        $plan = $this->weeklyScheduleService->savePlan(
            auth()->user(),
            $data,
            $data['weekdays']
        );

        $dayCount = $plan->days->count();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', "Weekly schedule saved — {$dayCount} day(s)/week, har hafte automatically chalega.");
    }

    public function editPlan(WeeklySchedulePlan $weeklySchedulePlan): View
    {
        $this->weeklyScheduleService->authorizePlan(auth()->user(), $weeklySchedulePlan);

        $weeklySchedulePlan->load(['route.busStand', 'vehicle', 'driver.user', 'days']);

        $options = $this->assignmentService->formOptionsFor(
            auth()->user(),
            $weeklySchedulePlan->route->bus_stand_id
        );

        $options = $this->assignmentService->withAssignedPlanResources($options, $weeklySchedulePlan);

        $initialWeekdays = $this->weeklyScheduleService->weekdaysForForm($weeklySchedulePlan);

        return view('admin.schedules.edit-plan', array_merge($options, [
            'plan' => $weeklySchedulePlan,
            'initialWeekdays' => $initialWeekdays,
        ]));
    }

    public function updatePlan(Request $request, WeeklySchedulePlan $weeklySchedulePlan): RedirectResponse
    {
        $this->weeklyScheduleService->authorizePlan(auth()->user(), $weeklySchedulePlan);

        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'fare' => 'required|numeric|min:0',
            'weekdays' => 'required|array',
            'weekdays.*.departure_time' => 'nullable|date_format:H:i',
            'weekdays.*.arrival_time' => 'nullable|date_format:H:i',
        ]);

        $plan = $this->weeklyScheduleService->savePlan(
            auth()->user(),
            $data,
            $data['weekdays'],
            $weeklySchedulePlan
        );

        $dayCount = $plan->days->count();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', "Weekly schedule updated — {$dayCount} day(s)/week.");
    }

    public function edit(Schedule $schedule): View
    {
        $this->authorizeSchedule($schedule);

        $schedule->load(['route.busStand', 'vehicle', 'driver.user']);

        $options = $this->assignmentService->formOptionsFor(
            auth()->user(),
            $schedule->route->bus_stand_id
        );

        return view('admin.schedules.edit', array_merge($options, compact('schedule')));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $this->authorizeSchedule($schedule);

        $schedule->load('route');

        if (in_array($schedule->status, ['departed', 'completed', 'cancelled'], true)) {
            return back()->withErrors([
                'vehicle_id' => 'Cannot change vehicle or driver after this trip has departed, completed, or been cancelled.',
            ]);
        }

        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'departure_date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'nullable|date_format:H:i',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,boarding,departed,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vehicle = $this->assignmentService->resolveVehicleForStand($data['vehicle_id'], $schedule->route->bus_stand_id);
        $driver = $this->assignmentService->resolveDriverForStand($data['driver_id'] ?? null, $schedule->route->bus_stand_id);
        $this->assignmentService->assertScheduleResourcesMatch($schedule->route, $vehicle, $driver);

        if ($this->scheduleRepository->hasConflict(
            $data['vehicle_id'],
            $data['departure_date'],
            $data['departure_time'],
            $schedule->id
        )) {
            return back()->withErrors(['departure_time' => 'Vehicle already scheduled at this time.'])->withInput();
        }

        $occupiedSeats = $this->occupiedSeatCount($schedule);

        if ($occupiedSeats > $vehicle->total_seats) {
            return back()->withErrors([
                'vehicle_id' => "This vehicle has {$vehicle->total_seats} seats but {$occupiedSeats} are already booked on this schedule.",
            ])->withInput();
        }

        $update = [
            'vehicle_id' => $vehicle->id,
            'driver_id' => $data['driver_id'] ?: null,
            'departure_date' => $data['departure_date'],
            'departure_time' => Carbon::parse($data['departure_time'])->format('H:i:s'),
            'arrival_time' => ! empty($data['arrival_time'])
                ? Carbon::parse($data['arrival_time'])->format('H:i:s')
                : null,
            'fare' => $data['fare'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ];

        if ((int) $vehicle->id !== (int) $schedule->vehicle_id) {
            $update['available_seats'] = max(0, $vehicle->total_seats - $occupiedSeats);
        }

        $schedule->update($update);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Trip updated.');
    }

    private function authorizeSchedule(Schedule $schedule): void
    {
        $schedule->loadMissing('route');

        abort_unless(
            auth()->user()->isSuperAdmin()
            || auth()->user()->ownsBusStand($schedule->route->bus_stand_id),
            403
        );
    }

    private function occupiedSeatCount(Schedule $schedule): int
    {
        return (int) $schedule->bookings()
            ->whereNotIn('status', [
                BookingStatus::Cancelled->value,
                BookingStatus::Refunded->value,
            ])
            ->withCount('passengers')
            ->get()
            ->sum('passengers_count');
    }
}
