<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusStand extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'owner_id', 'terminal_id', 'name', 'type', 'slug', 'address', 'city',
        'from_city', 'to_city',
        'phone', 'email', 'latitude', 'longitude', 'logo', 'images',
        'is_active', 'total_revenue', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'settings' => 'array',
            'is_active' => 'boolean',
            'total_revenue' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bus_stand_user')->withTimestamps();
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bus_stand_staff')
            ->withPivot('designation', 'is_active')
            ->withTimestamps();
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function conductors(): HasMany
    {
        return $this->hasMany(Conductor::class);
    }

    /** Primary label — stand name or location, never a route pair. */
    public function displayTitle(): string
    {
        if ($this->name && ! $this->looksLikeRouteName($this->name)) {
            return $this->name;
        }

        if ($this->address) {
            return $this->address;
        }

        return $this->name ?: 'Bus stand';
    }

    public function displaySubtitle(): string
    {
        return collect([
            $this->terminal?->name,
            $this->city,
            ucfirst($this->type),
        ])->filter()->implode(' · ');
    }

    public function looksLikeRouteName(?string $value): bool
    {
        if (! $value) {
            return false;
        }

        return (bool) preg_match('/\s*(→|->)\s*/u', $value);
    }

    public function hasLegacyRouteStyleName(): bool
    {
        return $this->looksLikeRouteName($this->name);
    }

    /** Value for the stand name field on edit forms (excludes legacy route-style names). */
    public function editableName(): string
    {
        if ($this->name && ! $this->looksLikeRouteName($this->name)) {
            return $this->name;
        }

        if ($this->hasLegacyRouteStyleName() && $this->address) {
            return $this->address;
        }

        return '';
    }

    /** @deprecated Use displayTitle() */
    public function routeLabel(): string
    {
        return $this->displayTitle();
    }
}
