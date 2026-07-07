<?php

namespace App\Services\BusStand;

use App\Enums\UserRole;
use App\Models\BusStand;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusStandOnboardingService
{
    /**
     * Create bus stand with a dedicated Bus Stand Admin login account.
     *
     * @return array{stand: BusStand, owner: User}
     */
    public function createWithOwner(array $standData, array $ownerData): array
    {
        return DB::transaction(function () use ($standData, $ownerData) {
            $owner = User::create([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'phone' => $ownerData['phone'] ?? null,
                'password' => Hash::make($ownerData['password']),
                'is_active' => true,
            ]);

            $owner->assignRole(UserRole::Admin->value);

            if (! empty($standData['terminal_id'])) {
                $owner->update(['terminal_id' => $standData['terminal_id']]);
            }

            $stand = BusStand::create([
                'owner_id' => $owner->id,
                'terminal_id' => $standData['terminal_id'],
                'name' => $standData['name'],
                'type' => $standData['type'],
                'slug' => $this->uniqueSlug($standData['name']),
                'address' => $standData['address'],
                'city' => $standData['city'],
                'phone' => $standData['phone'] ?? null,
                'email' => $standData['email'] ?? null,
                'latitude' => $standData['latitude'] ?? null,
                'longitude' => $standData['longitude'] ?? null,
                'is_active' => true,
            ]);

            $owner->assignedBusStands()->attach($stand->id);

            return ['stand' => $stand, 'owner' => $owner];
        });
    }

    public function createStandOnly(array $standData): BusStand
    {
        return BusStand::create([
            'owner_id' => null,
            'terminal_id' => $standData['terminal_id'],
            'name' => $standData['name'],
            'type' => $standData['type'],
            'slug' => $this->uniqueSlug($standData['name']),
            'address' => $standData['address'],
            'city' => $standData['city'],
            'phone' => $standData['phone'] ?? null,
            'email' => $standData['email'] ?? null,
            'latitude' => $standData['latitude'] ?? null,
            'longitude' => $standData['longitude'] ?? null,
            'is_active' => true,
        ]);
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
        $slug = $base.'-'.Str::lower(Str::random(4));

        while (BusStand::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(4));
        }

        return $slug;
    }
}
