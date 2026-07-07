<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'bus_stand_id', 'vehicle_category_id', 'driver_id', 'owner_name', 'owner_phone',
        'name', 'bus_number', 'registration_number', 'total_seats', 'bus_type',
        'is_ac', 'luxury_type', 'is_active', 'amenities',
    ];

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'is_ac' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function busStand(): BelongsTo
    {
        return $this->belongsTo(BusStand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function conductors(): BelongsToMany
    {
        return $this->belongsToMany(Conductor::class, 'vehicle_conductor')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function seatMaps(): HasMany
    {
        return $this->hasMany(SeatMap::class);
    }

    public function activeSeatMap(): ?SeatMap
    {
        return $this->seatMaps()->where('is_active', true)->first();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(VehicleMaintenanceLog::class);
    }
}
