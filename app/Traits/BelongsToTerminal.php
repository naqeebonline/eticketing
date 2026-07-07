<?php

namespace App\Traits;

use App\Models\BusStand;
use App\Models\Terminal;
use App\Models\User;

trait BelongsToTerminal
{
    protected function authorizeTerminalOwner(Terminal|BusStand $model): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        $terminalId = $model instanceof Terminal
            ? $model->id
            : $model->terminal_id;

        if ($user->isTerminalAdmin() && $terminalId && $user->ownsTerminal($terminalId)) {
            return;
        }

        abort(403, 'You do not have access to this terminal.');
    }

    protected function terminalIdForCurrentUser(): ?int
    {
        $user = auth()->user();

        if ($user instanceof User && $user->isTerminalAdmin()) {
            return $user->primaryTerminal()?->id;
        }

        return null;
    }
}
