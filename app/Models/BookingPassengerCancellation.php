<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassengerCancellation extends Model
{
    protected $fillable = [
        'booking_passenger_id',
        'booking_id',
        'seat_id',
        'seat_number',
        'fare',
        'action',
        'cancelled_by',
        'refund_id',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'fare' => 'decimal:2',
        ];
    }

    public function bookingPassenger(): BelongsTo
    {
        return $this->belongsTo(BookingPassenger::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function actionLabel(): string
    {
        return $this->action === 'refunded' ? 'Refunded' : 'Cancelled';
    }
}
