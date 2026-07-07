<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatMap extends Model
{
    protected $fillable = [
        'vehicle_id', 'name', 'rows', 'columns', 'layout', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'layout' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
