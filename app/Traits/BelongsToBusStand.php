<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToBusStand
{
  /**
   * Limit queries to the authenticated bus stand admin's stands.
   * Super Admin sees everything.
   */
    protected function scopeForBusStandAdmin(Builder $query, string $busStandColumn = 'bus_stand_id'): Builder
    {
        $user = auth()->user();

        if (! $user instanceof User || $user->isSuperAdmin()) {
            return $query;
        }

        $standIds = $user->manageableBusStandIds();

        if ($standIds !== null && $standIds !== []) {
            return $query->whereIn($busStandColumn, $standIds);
        }

        if ($standIds === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query;
    }

    protected function authorizeBusStandOwner($model): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        if ($user->isTerminalAdmin()) {
            $terminalId = $model->terminal_id ?? $model->busStand?->terminal_id ?? null;
            if ($terminalId && $user->ownsTerminal($terminalId)) {
                return;
            }
        }

        if ($user->isBusStandAdmin()) {
            $standId = $model->id ?? $model->bus_stand_id ?? null;
            if ($standId && $user->ownsBusStand($standId)) {
                return;
            }
        }

        if (isset($model->bus_stand_id) && $user->ownsBusStand($model->bus_stand_id)) {
            return;
        }

        abort(403, 'You do not have access to this resource.');
    }
}
