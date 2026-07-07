<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conductor extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id', 'bus_stand_id', 'name', 'phone', 'cnic',
        'employee_id', 'is_active',
    ];

    public function displayName(): string
    {
        return $this->name ?? $this->user?->name ?? 'Conductor #'.$this->id;
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function busStand(): BelongsTo
    {
        return $this->belongsTo(BusStand::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'vehicle_conductor')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'schedule_conductor');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(ConductorAttendance::class);
    }
}
