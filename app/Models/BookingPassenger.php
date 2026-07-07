<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id', 'seat_id', 'full_name', 'cnic', 'phone', 'email',
        'gender', 'passenger_type', 'fare', 'cancelled_at', 'cancelled_by',
    ];

    protected function casts(): array
    {
        return [
            'fare' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
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

    public function cancellationLog(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingPassengerCancellation::class);
    }
}
