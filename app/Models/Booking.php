<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'booking_number', 'user_id', 'schedule_id', 'status', 'payment_status',
        'subtotal', 'discount', 'tax', 'total_amount', 'paid_amount',
        'coupon_code', 'loyalty_points_used', 'loyalty_points_earned',
        'booking_source', 'booked_by', 'qr_code', 'hold_expires_at',
        'confirmed_at', 'cancelled_at', 'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookingStatus::class,
            'payment_status' => PaymentStatus::class,
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'hold_expires_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bookedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function passengerCancellations(): HasMany
    {
        return $this->hasMany(BookingPassengerCancellation::class)->latest();
    }

    public function activePassengers(): HasMany
    {
        return $this->passengers()->whereNull('cancelled_at');
    }

    public function isHeld(): bool
    {
        if ($this->status !== BookingStatus::Held) {
            return false;
        }

        return $this->hold_expires_at === null || $this->hold_expires_at->isFuture();
    }

    public function canReceiveCounterPayment(): bool
    {
        return $this->status === BookingStatus::Held
            && $this->payment_status === PaymentStatus::Pending
            && (float) $this->paid_amount < (float) $this->total_amount - 0.001;
    }

    public function ticketVerificationUrl(): string
    {
        return route('ticket.verify', $this->uuid);
    }

    public function isTicketVerifiable(): bool
    {
        return ! in_array($this->status, [BookingStatus::Cancelled, BookingStatus::Refunded], true);
    }
}
