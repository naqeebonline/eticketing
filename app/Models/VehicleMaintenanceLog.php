<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenanceLog extends Model
{
    protected $fillable = [
        'vehicle_id', 'type', 'description', 'cost',
        'maintenance_date', 'next_due_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'maintenance_date' => 'date',
            'next_due_date' => 'date',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
