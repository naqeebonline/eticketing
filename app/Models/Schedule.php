<?php

namespace App\Models;

use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'route_id', 'vehicle_id', 'driver_id', 'weekly_schedule_plan_id', 'departure_date',
        'departure_time', 'arrival_time', 'fare', 'available_seats',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'fare' => 'decimal:2',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function weeklyPlan(): BelongsTo
    {
        return $this->belongsTo(WeeklySchedulePlan::class, 'weekly_schedule_plan_id');
    }

    public function conductors(): BelongsToMany
    {
        return $this->belongsToMany(Conductor::class, 'schedule_conductor');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(VehicleTracking::class);
    }

    public function seatHolds(): HasMany
    {
        return $this->hasMany(SeatHold::class);
    }

    public function departureAt(): Carbon
    {
        $time = Carbon::parse($this->departure_time);

        return $this->departure_date->copy()->setTime(
            (int) $time->format('H'),
            (int) $time->format('i'),
            (int) $time->format('s'),
        );
    }

    public function hasDeparted(): bool
    {
        return now()->greaterThanOrEqualTo($this->departureAt());
    }

    public function isBookable(): bool
    {
        if ($this->available_seats < 1 || $this->status !== 'scheduled') {
            return false;
        }

        return ! $this->hasDeparted();
    }

    public function scopeBookable($query)
    {
        return $query
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->where(function ($q) {
                $q->whereDate('departure_date', '>', today())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('departure_date', today())
                            ->where('departure_time', '>', now()->format('H:i:s'));
                    });
            });
    }

    public function allowsSeatCancellation(): bool
    {
        if (in_array($this->status, ['departed', 'completed', 'cancelled'], true)) {
            return false;
        }

        return ! $this->hasDeparted();
    }
}
