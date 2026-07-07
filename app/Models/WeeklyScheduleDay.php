<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyScheduleDay extends Model
{
    protected $fillable = [
        'weekly_schedule_plan_id',
        'day_of_week',
        'departure_time',
        'arrival_time',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(WeeklySchedulePlan::class, 'weekly_schedule_plan_id');
    }

    public function weekdayLabel(): string
    {
        return WeeklySchedulePlan::weekdayLabels()[$this->day_of_week] ?? 'Day '.$this->day_of_week;
    }
}
