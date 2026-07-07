<?php

namespace App\Services\Booking;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Jobs\ReleaseExpiredSeatHolds;
use App\Jobs\SendBookingConfirmation;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BookingPassengerCancellation;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Models\Coupon;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\SeatHold;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private ScheduleRepositoryInterface $scheduleRepository,
        private SeatAvailabilityService $seatService,
    ) {}

    public function holdSeats(Schedule $schedule, array $seatIds, ?int $userId = null, ?string $sessionId = null): array
    {
        $sessionId = $sessionId ?? session()->getId();
        $holdMinutes = (int) config('bss.booking_hold_minutes', 10);
        $expiresAt = now()->addMinutes($holdMinutes);

        $this->seatService->validateSeatsAvailable($schedule, $seatIds, $userId, $sessionId);

        DB::transaction(function () use ($schedule, $seatIds, $userId, $sessionId, $expiresAt) {
            foreach ($seatIds as $seatId) {
                SeatHold::updateOrCreate(
                    ['schedule_id' => $schedule->id, 'seat_id' => $seatId],
                    [
                        'session_id' => $sessionId,
                        'user_id' => $userId,
                        'expires_at' => $expiresAt,
                    ]
                );
            }
        });

        ReleaseExpiredSeatHolds::dispatch()->delay($expiresAt);

        return [
            'session_id' => $sessionId,
            'expires_at' => $expiresAt->toIso8601String(),
            'seat_ids' => $seatIds,
        ];
    }

    /**
     * @param  list<int>  $seatIds
     * @return list<array{seat_id: int, full_name: string, cnic: ?string, phone: ?string, gender: string, passenger_type: string}>
     */
    public function buildPassengersFromGroup(array $seatIds, array $contact, int $maleCount, int $femaleCount, int $childCount): array
    {
        $travelers = array_merge(
            array_fill(0, $maleCount, ['gender' => 'male', 'passenger_type' => 'adult']),
            array_fill(0, $femaleCount, ['gender' => 'female', 'passenger_type' => 'adult']),
            array_fill(0, $childCount, ['gender' => 'male', 'passenger_type' => 'child']),
        );

        if (count($travelers) !== count($seatIds)) {
            throw new \InvalidArgumentException('Traveler counts must match the number of selected seats.');
        }

        $childIndex = 1;
        $passengers = [];

        foreach ($seatIds as $index => $seatId) {
            $traveler = $travelers[$index];
            $name = trim($contact['full_name']);

            if ($traveler['passenger_type'] === 'child') {
                $name .= ' (Child '.$childIndex.')';
                $childIndex++;
            }

            $passengers[] = [
                'seat_id' => (int) $seatId,
                'full_name' => $name,
                'cnic' => $contact['cnic'] ?? null,
                'phone' => $contact['phone'] ?? null,
                'gender' => $traveler['gender'],
                'passenger_type' => $traveler['passenger_type'],
            ];
        }

        return $passengers;
    }

    public function createBooking(Schedule $schedule, array $passengers, array $options = []): Booking
    {
        return DB::transaction(function () use ($schedule, $passengers, $options) {
            $seatIds = collect($passengers)->pluck('seat_id')->map(fn ($id) => (int) $id)->all();
            $this->seatService->validateSeatsAvailable(
                $schedule,
                $seatIds,
                $options['user_id'] ?? auth()->id(),
                session()->getId(),
            );

            $subtotal = 0;
            foreach ($passengers as &$passenger) {
                $seat = Seat::findOrFail($passenger['seat_id']);
                $fare = $seat->fareForSchedule($schedule);
                $passenger['fare'] = $fare;
                $subtotal += $fare;
            }

            $discount = 0;
            if (! empty($options['coupon_code'])) {
                $coupon = Coupon::where('code', $options['coupon_code'])->first();
                if ($coupon?->isValid($subtotal)) {
                    $discount = $coupon->type === 'percentage'
                        ? $subtotal * ($coupon->value / 100)
                        : min($coupon->value, $subtotal);
                    $coupon->increment('used_count');
                }
            }

            $tax = $options['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;

            $booking = $this->bookingRepository->create([
                'booking_number' => $this->generateBookingNumber(),
                'user_id' => $options['user_id'] ?? auth()->id(),
                'schedule_id' => $schedule->id,
                'status' => BookingStatus::Held,
                'payment_status' => PaymentStatus::Pending,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $total,
                'coupon_code' => $options['coupon_code'] ?? null,
                'booking_source' => $options['source'] ?? 'online',
                'booked_by' => $options['booked_by'] ?? null,
                'hold_expires_at' => array_key_exists('hold_expires_at', $options)
                    ? $options['hold_expires_at']
                    : now()->addMinutes((int) config('bss.booking_hold_minutes', 10)),
            ]);

            foreach ($passengers as $passengerData) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $passengerData['seat_id'],
                    'full_name' => $passengerData['full_name'],
                    'cnic' => $passengerData['cnic'] ?? null,
                    'phone' => $passengerData['phone'] ?? null,
                    'email' => $passengerData['email'] ?? null,
                    'gender' => $passengerData['gender'],
                    'passenger_type' => $passengerData['passenger_type'] ?? 'adult',
                    'fare' => $passengerData['fare'],
                ]);
            }

            SeatHold::where('schedule_id', $schedule->id)
                ->whereIn('seat_id', $seatIds)
                ->delete();

            $schedule->decrement('available_seats', count($seatIds));

            return $booking->load('passengers.seat');
        });
    }

    public function confirmBooking(Booking $booking): Booking
    {
        $booking->update([
            'status' => BookingStatus::Confirmed,
            'payment_status' => PaymentStatus::Paid,
            'confirmed_at' => now(),
            'hold_expires_at' => null,
            'qr_code' => $this->generateQrCode($booking),
        ]);

        SendBookingConfirmation::dispatch($booking);

        return $booking->fresh();
    }

    public function wasPassengerFarePaid(Booking $booking, BookingPassenger $passenger): bool
    {
        $paidRemaining = (float) $booking->paid_amount;

        $activePassengers = $booking->passengers()
            ->whereNull('cancelled_at')
            ->orderBy('id')
            ->get();

        foreach ($activePassengers as $activePassenger) {
            $fare = (float) $activePassenger->fare;

            if ($activePassenger->id === $passenger->id) {
                return $paidRemaining >= $fare - 0.001;
            }

            $paidRemaining -= min($paidRemaining, $fare);
        }

        return false;
    }

    public function cancelPassenger(BookingPassenger $passenger, User $cancelledBy, ?string $reason = null): BookingPassengerCancellation
    {
        return DB::transaction(fn () => $this->performPassengerCancellation($passenger, $cancelledBy, $reason));
    }

    /**
     * @param  list<int>  $passengerIds
     * @return list<BookingPassengerCancellation>
     */
    public function cancelPassengers(Booking $booking, array $passengerIds, User $cancelledBy, ?string $reason = null): array
    {
        return DB::transaction(function () use ($booking, $passengerIds, $cancelledBy, $reason) {
            $ids = array_values(array_unique(array_map('intval', $passengerIds)));

            $passengers = BookingPassenger::query()
                ->where('booking_id', $booking->id)
                ->whereIn('id', $ids)
                ->whereNull('cancelled_at')
                ->orderBy('id')
                ->get();

            if ($passengers->isEmpty()) {
                throw new \InvalidArgumentException('No valid seats selected for cancellation.');
            }

            if ($passengers->count() !== count($ids)) {
                throw new \InvalidArgumentException('One or more selected seats are invalid or already cancelled.');
            }

            $logs = [];

            foreach ($passengers as $passenger) {
                $booking->refresh();
                $passenger->refresh();
                $logs[] = $this->performPassengerCancellation($passenger, $cancelledBy, $reason);
            }

            return $logs;
        });
    }

    private function performPassengerCancellation(BookingPassenger $passenger, User $cancelledBy, ?string $reason = null): BookingPassengerCancellation
    {
        $passenger->load(['booking.schedule', 'seat']);
        $booking = $passenger->booking;

        if ($passenger->isCancelled()) {
            throw new \InvalidArgumentException('This seat is already cancelled.');
        }

        if (in_array($booking->status, [BookingStatus::Cancelled, BookingStatus::Completed], true)) {
            throw new \InvalidArgumentException('This booking cannot be modified.');
        }

        if (! $booking->schedule->allowsSeatCancellation()) {
            throw new \InvalidArgumentException('Seat cancellation is only allowed before departure.');
        }

        $fare = (float) $passenger->fare;
        $wasPaid = $this->wasPassengerFarePaid($booking, $passenger);
        $action = $wasPaid ? 'refunded' : 'cancelled';
        $seatNumber = $passenger->seat?->seat_number;
        $refund = null;

        if ($wasPaid) {
            $refund = app(PaymentService::class)->refundSeatFare(
                $booking,
                $fare,
                $cancelledBy->id,
                $reason ?? 'Seat '.$seatNumber.' cancelled'
            );
        }

        $passenger->update([
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy->id,
        ]);

        $booking->update([
            'subtotal' => max(0, (float) $booking->subtotal - $fare),
            'total_amount' => max(0, (float) $booking->total_amount - $fare),
        ]);

        $booking->schedule->increment('available_seats', 1);

        $activeRemaining = $booking->passengers()->whereNull('cancelled_at')->count();
        if ($activeRemaining === 0) {
            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason ?? 'All seats cancelled',
            ]);
        }

        return BookingPassengerCancellation::create([
            'booking_passenger_id' => $passenger->id,
            'booking_id' => $booking->id,
            'seat_id' => $passenger->seat_id,
            'seat_number' => $seatNumber,
            'fare' => $fare,
            'action' => $action,
            'cancelled_by' => $cancelledBy->id,
            'refund_id' => $refund?->id,
            'reason' => $reason,
        ]);
    }

    public function cancelBooking(Booking $booking, ?string $reason = null): Booking
    {
        return DB::transaction(function () use ($booking, $reason) {
            $booking->loadMissing('schedule');

            if (! $booking->schedule->allowsSeatCancellation()) {
                throw new \InvalidArgumentException('Booking cancellation is only allowed before departure.');
            }

            $seatCount = $booking->passengers()->count();

            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            $booking->schedule->increment('available_seats', $seatCount);

            return $booking->fresh();
        });
    }

    private function generateBookingNumber(): string
    {
        $prefix = config('bss.booking_prefix', 'BSS');

        return $prefix.'-'.strtoupper(Str::random(8)).'-'.now()->format('ymd');
    }

    private function generateQrCode(Booking $booking): string
    {
        return $booking->ticketVerificationUrl();
    }
}
