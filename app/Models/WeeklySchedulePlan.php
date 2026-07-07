<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklySchedulePlan extends Model
{
    protected $fillable = [
        'route_id',
        'vehicle_id',
        'driver_id',
        'fare',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fare' => 'decimal:2',
            'is_active' => 'boolean',
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

    public function days(): HasMany
    {
        return $this->hasMany(WeeklyScheduleDay::class)->orderBy('day_of_week');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /** @return list<string> */
    public static function weekdayLabels(): array
    {
        return [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];
    }

    public function activeDayCount(): int
    {
        return $this->days()->count();
    }
}
