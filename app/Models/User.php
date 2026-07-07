<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasUuid, LogsActivity, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'cnic', 'password', 'avatar', 'terminal_id',
        'locale', 'theme', 'is_active', 'last_login_at', 'last_login_ip',
        'email_verified_at', 'phone_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name', 'email', 'is_active']);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function assignedBusStands(): BelongsToMany
    {
        return $this->belongsToMany(BusStand::class, 'bus_stand_user')->withTimestamps();
    }

    /** @deprecated Use assignedBusStands() */
    public function ownedBusStands(): BelongsToMany
    {
        return $this->assignedBusStands();
    }

    public function ownedTerminals(): HasMany
    {
        return $this->hasMany(Terminal::class, 'owner_id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function conductor(): HasOne
    {
        return $this->hasOne(Conductor::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRole::SuperAdmin->value);
    }

    public function isTerminalAdmin(): bool
    {
        return $this->hasRole(UserRole::TerminalAdmin->value);
    }

    public function isBusStandAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin->value);
    }

    /** Super Admin, Terminal Admin, or Bus Stand Admin — admin panel */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isTerminalAdmin() || $this->isBusStandAdmin();
    }

    public function isPassenger(): bool
    {
        return $this->hasRole(UserRole::Passenger->value);
    }

    public function roleLabel(): string
    {
        $role = $this->roles->first()?->name;

        return match ($role) {
            UserRole::SuperAdmin->value => 'Super Admin',
            UserRole::TerminalAdmin->value => 'Terminal / Adda Admin',
            UserRole::Admin->value => 'Bus Stand Admin',
            UserRole::Passenger->value => 'Passenger',
            default => ucfirst(str_replace('_', ' ', $role ?? 'User')),
        };
    }

    /** Bus stand IDs this user may manage; null = all (Super Admin). */
    public function manageableBusStandIds(): ?array
    {
        if ($this->isSuperAdmin()) {
            return null;
        }

        if ($this->isTerminalAdmin()) {
            return BusStand::query()
                ->whereIn('terminal_id', $this->ownedTerminals()->pluck('id'))
                ->pluck('id')
                ->all();
        }

        if ($this->isBusStandAdmin()) {
            return $this->assignedBusStands()->pluck('bus_stands.id')->all();
        }

        return [];
    }

    public function primaryTerminal(): ?Terminal
    {
        return $this->ownedTerminals()->first();
    }

    public function primaryBusStand(): ?BusStand
    {
        return $this->assignedBusStands()->first();
    }

    public function ownsTerminal(int|string $terminalId): bool
    {
        return $this->ownedTerminals()->where('id', $terminalId)->exists();
    }

    public function ownsBusStand(int|string $busStandId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->assignedBusStands()->where('bus_stands.id', $busStandId)->exists()) {
            return true;
        }

        if ($this->isTerminalAdmin()) {
            return BusStand::query()
                ->where('id', $busStandId)
                ->whereIn('terminal_id', $this->ownedTerminals()->pluck('id'))
                ->exists();
        }

        return false;
    }
}
