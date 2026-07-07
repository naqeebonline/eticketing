<?php

namespace App\Contracts\Repositories;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    public function findByUuid(string $uuid): ?Booking;

    public function findByBookingNumber(string $number): ?Booking;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function getBySchedule(int $scheduleId): Collection;
}
