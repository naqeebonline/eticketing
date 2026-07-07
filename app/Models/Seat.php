<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $fillable = [
        'seat_map_id', 'seat_number', 'row', 'column',
        'type', 'fare_amount', 'fare_multiplier', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fare_amount' => 'decimal:2',
            'fare_multiplier' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function fareForSchedule(\App\Models\Schedule $schedule): float
    {
        if ($this->fare_amount !== null && (float) $this->fare_amount > 0) {
            return (float) $this->fare_amount;
        }

        return (float) $schedule->fare * (float) $this->fare_multiplier;
    }

    public function rowTypeLabel(): string
    {
        return match ($this->type) {
            'luxury' => 'Luxury',
            'normal' => 'Normal',
            default => ucfirst($this->type),
        };
    }

    public function seatMap(): BelongsTo
    {
        return $this->belongsTo(SeatMap::class);
    }

    public function bookingPassengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function holds(): HasMany
    {
        return $this->hasMany(SeatHold::class);
    }
}
