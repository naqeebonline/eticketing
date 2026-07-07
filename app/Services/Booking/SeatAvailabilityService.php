<?php

namespace App\Services\Booking;

use App\Enums\BookingStatus;
use App\Models\BookingPassenger;
use App\Models\Schedule;
use App\Models\SeatHold;
use App\Services\Vehicle\SeatLayoutService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class SeatAvailabilityService
{
    public function __construct(
        private SeatLayoutService $seatLayoutService,
    ) {}

    /** @var list<string> */
    private const BLOCKING_BOOKING_STATUSES = [
        BookingStatus::Pending->value,
        BookingStatus::Held->value,
        BookingStatus::Confirmed->value,
    ];

    public function getSeatMapWithStatus(Schedule $schedule): array
    {
        SeatHold::where('expires_at', '<', now())->delete();

        $schedule->loadMissing('vehicle.seatMaps.seats');

        $seatMap = $schedule->vehicle?->activeSeatMap();
        if ($seatMap) {
            $seatMap->load('seats');
        }

        if (! $seatMap || $seatMap->seats->isEmpty()) {
            return ['seats' => [], 'layout' => [], 'rows' => 0, 'columns' => 0, 'seat_rows' => []];
        }

        $bookedSeatIds = $this->getBookedSeatIds($schedule);
        $myHoldSeatIds = $this->getHeldSeatIdsForSession($schedule, auth()->id(), session()->getId());
        $otherHeldSeatIds = SeatHold::where('schedule_id', $schedule->id)
            ->where('expires_at', '>', now())
            ->whereNotIn('seat_id', $myHoldSeatIds)
            ->pluck('seat_id');

        $seats = $seatMap->seats->map(function ($seat) use ($bookedSeatIds, $myHoldSeatIds, $otherHeldSeatIds, $schedule) {
            $status = 'available';

            if ($bookedSeatIds->contains($seat->id)) {
                $status = 'booked';
            } elseif ($otherHeldSeatIds->contains($seat->id)) {
                $status = 'held';
            } elseif ($myHoldSeatIds->contains($seat->id)) {
                $status = 'available';
            }

            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'row' => $seat->row,
                'column' => $seat->column,
                'type' => $seat->type,
                'fare' => $seat->fareForSchedule($schedule),
                'status' => $status,
            ];
        })->values();

        $layout = is_array($seatMap->layout) ? $seatMap->layout : [];

        return [
            'seat_map_id' => $seatMap->id,
            'rows' => $seatMap->rows,
            'columns' => $seatMap->columns,
            'layout' => $layout,
            'seats' => $seats,
            'seat_rows' => $this->seatLayoutService->groupSeatsForDisplay($seats, $layout),
        ];
    }

    public function validateSeatsAvailable(
        Schedule $schedule,
        array $seatIds,
        ?int $userId = null,
        ?string $sessionId = null,
    ): void {
        $sessionId = $sessionId ?? session()->getId();
        $userId = $userId ?? auth()->id();
        $seatIds = array_map('intval', $seatIds);

        $seatMap = $schedule->vehicle?->activeSeatMap();
        if ($seatMap) {
            $seatMap->load('seats');
        }

        if (! $seatMap) {
            throw ValidationException::withMessages([
                'seats' => ['No seat map is configured for this bus.'],
            ]);
        }

        $validSeatIds = $seatMap->seats()->whereIn('id', $seatIds)->pluck('id');
        if ($validSeatIds->count() !== count($seatIds)) {
            throw ValidationException::withMessages([
                'seats' => ['One or more selected seats are invalid for this bus.'],
            ]);
        }

        $unavailable = collect($seatIds)->filter(
            fn (int $seatId) => ! $this->isSeatReservable($schedule, $seatId, $userId, $sessionId)
        );

        if ($unavailable->isNotEmpty()) {
            throw ValidationException::withMessages([
                'seats' => ['One or more selected seats are no longer available.'],
            ]);
        }
    }

    public function getOccupiedSeats(Schedule $schedule): Collection
    {
        return $this->getBookedSeatIds($schedule);
    }

    private function isSeatReservable(
        Schedule $schedule,
        int $seatId,
        ?int $userId,
        ?string $sessionId,
    ): bool {
        if ($this->getBookedSeatIds($schedule)->contains($seatId)) {
            return false;
        }

        $activeHold = SeatHold::where('schedule_id', $schedule->id)
            ->where('seat_id', $seatId)
            ->where('expires_at', '>', now())
            ->first();

        if (! $activeHold) {
            return true;
        }

        if ($activeHold->session_id === $sessionId) {
            return true;
        }

        if ($userId && $activeHold->user_id === $userId) {
            return true;
        }

        return false;
    }

    private function getBookedSeatIds(Schedule $schedule): Collection
    {
        return BookingPassenger::query()
            ->whereNull('cancelled_at')
            ->whereHas('booking', function ($q) use ($schedule) {
                $q->where('schedule_id', $schedule->id)
                    ->whereIn('status', self::BLOCKING_BOOKING_STATUSES);
            })
            ->pluck('seat_id');
    }

    private function getHeldSeatIdsForSession(Schedule $schedule, ?int $userId, ?string $sessionId): Collection
    {
        return SeatHold::where('schedule_id', $schedule->id)
            ->where('expires_at', '>', now())
            ->where(function ($q) use ($userId, $sessionId) {
                $q->where('session_id', $sessionId);
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            })
            ->pluck('seat_id');
    }
}
