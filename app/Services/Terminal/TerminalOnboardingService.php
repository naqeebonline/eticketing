<?php

namespace App\Services\Terminal;

use App\Enums\UserRole;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TerminalOnboardingService
{
    /**
     * @return array{terminal: Terminal, owner: User}
     */
    public function createWithOwner(array $terminalData, array $ownerData): array
    {
        return DB::transaction(function () use ($terminalData, $ownerData) {
            $owner = User::create([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'phone' => $ownerData['phone'] ?? null,
                'password' => Hash::make($ownerData['password']),
                'is_active' => true,
            ]);

            $owner->assignRole(UserRole::TerminalAdmin->value);

            $terminal = Terminal::create([
                'owner_id' => $owner->id,
                'name' => $terminalData['name'],
                'slug' => $this->uniqueSlug($terminalData['name']),
                'city' => $terminalData['city'],
                'address' => $terminalData['address'] ?? null,
                'phone' => $terminalData['phone'] ?? null,
                'email' => $terminalData['email'] ?? null,
                'is_active' => $terminalData['is_active'] ?? true,
                'sort_order' => $terminalData['sort_order'] ?? 0,
            ]);

            return ['terminal' => $terminal, 'owner' => $owner];
        });
    }

    public function updateOwner(User $owner, array $ownerData): User
    {
        $owner->update([
            'name' => $ownerData['name'],
            'email' => $ownerData['email'],
            'phone' => $ownerData['phone'] ?? null,
        ]);

        if (! empty($ownerData['password'])) {
            $owner->update(['password' => Hash::make($ownerData['password'])]);
        }

        return $owner->fresh();
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Terminal::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
