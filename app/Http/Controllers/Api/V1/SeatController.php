<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\Booking\BookingService;
use App\Services\Booking\SeatAvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository,
        private SeatAvailabilityService $seatService,
        private BookingService $bookingService,
    ) {}

    public function index(string $scheduleUuid): JsonResponse
    {
        $schedule = $this->scheduleRepository->findByUuid($scheduleUuid);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found.'], 404);
        }

        return response()->json($this->seatService->getSeatMapWithStatus($schedule));
    }

    public function hold(Request $request, string $scheduleUuid): JsonResponse
    {
        $request->validate([
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'integer|exists:seats,id',
        ]);

        $schedule = $this->scheduleRepository->findByUuid($scheduleUuid);

        if (! $schedule) {
            return response()->json(['message' => 'Schedule not found.'], 404);
        }

        $result = $this->bookingService->holdSeats(
            $schedule,
            $request->seat_ids,
            $request->user()?->id,
            $request->header('X-Session-ID')
        );

        return response()->json($result);
    }
}
