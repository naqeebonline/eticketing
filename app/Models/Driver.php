<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id', 'bus_stand_id', 'name', 'phone', 'cnic',
        'license_number', 'license_expiry', 'license_class', 'address',
        'emergency_contact', 'is_active',
    ];

    public function displayName(): string
    {
        return $this->name ?? $this->user?->name ?? 'Driver #'.$this->id;
    }

    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function busStand(): BelongsTo
    {
        return $this->belongsTo(BusStand::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
