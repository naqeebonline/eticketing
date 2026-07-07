<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConductorAttendance extends Model
{
    protected $table = 'conductor_attendance';

    protected $fillable = [
        'conductor_id', 'date', 'check_in', 'check_out', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class);
    }
}
