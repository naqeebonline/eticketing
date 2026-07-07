<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'bus_stand_id', 'code', 'departure_city', 'destination_city',
        'name', 'distance_km', 'duration_minutes', 'base_fare',
        'map_polyline', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'map_polyline' => 'array',
            'is_active' => 'boolean',
            'distance_km' => 'decimal:2',
            'base_fare' => 'decimal:2',
        ];
    }

    public function busStand(): BelongsTo
    {
        return $this->belongsTo(BusStand::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(RouteStop::class)->orderBy('order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function displayLabel(): string
    {
        return "{$this->name} ({$this->departure_city} → {$this->destination_city})";
    }
}
