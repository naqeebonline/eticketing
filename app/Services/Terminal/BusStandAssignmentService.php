<?php

namespace App\Services\Terminal;

use App\Enums\UserRole;
use App\Models\BusStand;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class BusStandAssignmentService
{
    /**
     * Sync which stands this user may manage (many users per stand allowed).
     *
     * @param  array<int>  $standIds
     */
    public function syncStandsForUser(User $user, array $standIds, int $terminalId): void
    {
        $standIds = array_values(array_unique(array_map('intval', $standIds)));

        $standsInTerminal = BusStand::query()
            ->where('terminal_id', $terminalId)
            ->whereIn('id', $standIds)
            ->pluck('id')
            ->all();

        if (count($standsInTerminal) !== count($standIds)) {
            throw ValidationException::withMessages([
                'stand_ids' => 'One or more stands do not belong to this terminal.',
            ]);
        }

        $existingIds = $user->assignedBusStands()
            ->where('bus_stands.terminal_id', $terminalId)
            ->pluck('bus_stands.id')
            ->all();

        $toDetach = array_diff($existingIds, $standIds);
        $toAttach = array_diff($standIds, $existingIds);

        if ($toDetach !== []) {
            foreach ($toDetach as $standId) {
                $stand = BusStand::find($standId);
                if ($stand) {
                    $this->unassignStand($stand, $user);
                }
            }
        }

        if ($toAttach !== []) {
            foreach ($toAttach as $standId) {
                $stand = BusStand::find($standId);
                if ($stand) {
                    $this->assignStandToUser($stand, $user);
                }
            }
        }
    }

    public function assignStandToUser(BusStand $stand, User $user): void
    {
        $user->assignedBusStands()->syncWithoutDetaching([$stand->id]);

        if (! $stand->owner_id) {
            $stand->update(['owner_id' => $user->id]);
        }
    }

    /**
     * @param  array<int>  $userIds
     */
    public function syncUsersForStand(BusStand $stand, array $userIds): void
    {
        $userIds = array_values(array_unique(array_map('intval', $userIds)));

        $validIds = User::query()
            ->whereIn('id', $userIds)
            ->where('terminal_id', $stand->terminal_id)
            ->whereHas('roles', fn ($q) => $q->where('name', UserRole::Admin->value))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($validIds) !== count($userIds)) {
            throw ValidationException::withMessages([
                'user_ids' => ['One or more users are invalid for this terminal.'],
            ]);
        }

        $currentIds = $stand->assignedUsers()
            ->pluck('users.id')
            ->map(fn ($id) => (int) $id)
            ->all();

        foreach (array_diff($currentIds, $validIds) as $detachId) {
            $user = User::find($detachId);
            if ($user) {
                $this->unassignStand($stand, $user);
            }
        }

        foreach (array_diff($validIds, $currentIds) as $attachId) {
            $user = User::findOrFail($attachId);
            $this->assignStandToUser($stand, $user);
        }
    }

    public function unassignStand(BusStand $stand, ?User $user = null): void
    {
        if ($user) {
            $user->assignedBusStands()->detach($stand->id);
            if ($stand->owner_id === $user->id) {
                $stand->update(['owner_id' => null]);
            }

            return;
        }

        if ($stand->owner_id) {
            User::find($stand->owner_id)?->assignedBusStands()->detach($stand->id);
            $stand->update(['owner_id' => null]);
        }
    }
}
