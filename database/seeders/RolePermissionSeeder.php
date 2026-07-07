<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'terminals.manage',
            'bus-stands.manage',
            'vehicles.manage',
            'routes.manage',
            'schedules.manage',
            'bookings.manage',
            'bookings.view',
            'payments.manage',
            'reports.view',
            'users.manage',
            'drivers.manage',
            'conductors.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }

        $terminalAdminPermissions = [
            'terminals.manage',
            'bus-stands.manage',
            'bookings.view',
            'reports.view',
        ];

        $busStandAdminPermissions = [
            'vehicles.manage',
            'routes.manage',
            'schedules.manage',
            'bookings.manage',
            'bookings.view',
            'reports.view',
            'drivers.manage',
            'conductors.manage',
        ];

        $roles = [
            'super_admin' => $permissions,
            'terminal_admin' => $terminalAdminPermissions,
            'admin' => $busStandAdminPermissions,
            'passenger' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);

            $apiRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
            $apiRole->syncPermissions($rolePermissions);
        }

        $this->migrateLegacyRoles();

        Role::whereIn('name', ['bus_company', 'stand_owner', 'staff', 'driver', 'conductor'])
            ->where('guard_name', 'web')
            ->delete();
        Role::whereIn('name', ['bus_company', 'stand_owner', 'staff', 'driver', 'conductor'])
            ->where('guard_name', 'sanctum')
            ->delete();
    }

    /** Map old multi-role setup to the new 4-role model */
    private function migrateLegacyRoles(): void
    {
        $legacyAdminRoles = ['bus_company', 'stand_owner', 'staff', 'driver', 'conductor'];

        User::all()->each(function (User $user) use ($legacyAdminRoles) {
            if ($user->hasAnyRole($legacyAdminRoles) && ! $user->hasRole('super_admin')) {
                $user->syncRoles(['admin']);
            }
        });
    }
}
