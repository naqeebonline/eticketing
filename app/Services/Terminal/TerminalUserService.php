<?php

namespace App\Services\Terminal;

use App\Enums\UserRole;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TerminalUserService
{
    public function __construct(
        private BusStandAssignmentService $assignmentService,
    ) {}

    /**
     * @return array{user: User}
     */
    public function createForTerminal(Terminal $terminal, array $userData, array $standIds = []): array
    {
        return DB::transaction(function () use ($terminal, $userData, $standIds) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
                'password' => Hash::make($userData['password']),
                'terminal_id' => $terminal->id,
                'is_active' => true,
            ]);

            $user->assignRole(UserRole::Admin->value);

            if ($standIds !== []) {
                $this->assignmentService->syncStandsForUser($user, $standIds, $terminal->id);
            }

            return ['user' => $user];
        });
    }

    public function updateUser(User $user, array $userData, array $standIds, int $terminalId): User
    {
        return DB::transaction(function () use ($user, $userData, $standIds, $terminalId) {
            $user->update([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
            ]);

            if (! empty($userData['password'])) {
                $user->update(['password' => Hash::make($userData['password'])]);
            }

            $this->assignmentService->syncStandsForUser($user, $standIds, $terminalId);

            return $user->fresh();
        });
    }

    /** Users created under this terminal (Bus Stand Admin role). */
    public function busStandAdminsFor(Terminal $terminal)
    {
        return User::query()
            ->role(UserRole::Admin->value)
            ->where('terminal_id', $terminal->id)
            ->withCount('assignedBusStands')
            ->orderBy('name')
            ->get();
    }
}
