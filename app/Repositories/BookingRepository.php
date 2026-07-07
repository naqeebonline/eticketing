<?php

namespace App\Repositories;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    public function findByUuid(string $uuid): ?Booking
    {
        return Booking::with([
            'schedule.route',
            'schedule.vehicle',
            'passengers.seat',
            'passengers.cancelledByUser',
            'passengerCancellations.cancelledByUser',
            'payments',
        ])
            ->where('uuid', $uuid)
            ->first();
    }

    public function findByBookingNumber(string $number): ?Booking
    {
        return Booking::with(['schedule.route', 'passengers.seat'])
            ->where('booking_number', $number)
            ->first();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Booking::query()
            ->with(['user', 'schedule.route', 'schedule.vehicle', 'passengers'])
            ->withCount(['passengers as active_passengers_count' => fn ($q) => $q->whereNull('cancelled_at')]);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['vehicle_id'])) {
            $query->whereHas('schedule', fn ($q) => $q->where('vehicle_id', $filters['vehicle_id']));
        }

        if (! empty($filters['route_id'])) {
            $query->whereHas('schedule', fn ($q) => $q->where('route_id', $filters['route_id']));
        }

        if (! empty($filters['departure_date'])) {
            $query->whereHas('schedule', fn ($q) => $q->whereDate('departure_date', $filters['departure_date']));
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('booking_number', 'like', $term)
                    ->orWhereHas('passengers', fn ($p) => $p->where('full_name', 'like', $term)
                        ->orWhere('cnic', 'like', $term)
                        ->orWhere('phone', 'like', $term));
            });
        }

        if (! empty($filters['bus_stand_ids'])) {
            $query->whereHas('schedule.route', fn ($q) => $q->whereIn('bus_stand_id', $filters['bus_stand_ids']));
        }

        $query
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->leftJoin('vehicles', 'schedules.vehicle_id', '=', 'vehicles.id')
            ->select('bookings.*')
            ->orderBy('vehicles.name')
            ->orderBy('schedules.departure_date')
            ->orderBy('schedules.departure_time')
            ->orderByDesc('bookings.created_at');

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh();
    }

    public function getBySchedule(int $scheduleId): Collection
    {
        return Booking::where('schedule_id', $scheduleId)
            ->whereIn('status', ['confirmed', 'held', 'pending'])
            ->with('passengers')
            ->get();
    }
}
