<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\BusStand;
use App\Models\Coupon;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\SeatMap;
use App\Models\Terminal;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\Vehicle\SeatLayoutService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bssbooking.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '03001234567',
                'is_active' => true,
            ]
        );
        $superAdmin->syncRoles([UserRole::SuperAdmin->value]);

        $terminalAdmin = User::updateOrCreate(
            ['email' => 'terminal@bssbooking.com'],
            [
                'name' => 'Saddar Terminal Admin',
                'password' => Hash::make('password'),
                'phone' => '03001112222',
                'is_active' => true,
            ]
        );
        $terminalAdmin->syncRoles([UserRole::TerminalAdmin->value]);

        $standAdmin = User::updateOrCreate(
            ['email' => 'stand@bssbooking.com'],
            [
                'name' => 'Karachi Bus Stand Admin',
                'password' => Hash::make('password'),
                'phone' => '03009876543',
                'is_active' => true,
            ]
        );
        $standAdmin->syncRoles([UserRole::Admin->value]);

        User::where('email', 'company@bssbooking.com')->each(function (User $user) {
            if (! $user->hasAnyRole(['super_admin', 'terminal_admin', 'admin'])) {
                $user->syncRoles([UserRole::Admin->value]);
            }
        });

        User::firstOrCreate(
            ['email' => 'passenger@bssbooking.com'],
            [
                'name' => 'Demo Passenger',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        )->syncRoles([UserRole::Passenger->value]);

        $terminal = Terminal::query()->where('slug', 'karachi-saddar-terminal')->first()
            ?? Terminal::query()->active()->first();

        if ($terminal) {
            $terminal->update(['owner_id' => $terminalAdmin->id]);
        }

        if ($terminal) {
            $standAdmin->update(['terminal_id' => $terminal->id]);
        }

        $stand = BusStand::updateOrCreate(
            ['slug' => 'metro-express-karachi'],
            [
                'owner_id' => $standAdmin->id,
                'terminal_id' => $terminal?->id,
                'name' => 'Karachi → Lahore',
                'type' => 'company',
                'address' => 'M.A. Jinnah Road Counter',
                'city' => 'Karachi',
                'from_city' => 'Karachi',
                'to_city' => 'Lahore',
                'phone' => '021-111222333',
                'email' => 'info@metrobus.com',
                'is_active' => true,
            ]
        );

        $standAdmin->assignedBusStands()->syncWithoutDetaching([$stand->id]);

        $category = VehicleCategory::firstOrCreate(
            ['slug' => 'standard'],
            ['name' => 'Standard', 'description' => 'Standard coach']
        );

        $vehicle = Vehicle::firstOrCreate(
            ['registration_number' => 'KHI-2024-001'],
            [
                'bus_stand_id' => $stand->id,
                'vehicle_category_id' => $category->id,
                'name' => 'Metro Express',
                'bus_number' => 'ME-101',
                'total_seats' => 40,
                'bus_type' => 'standard',
                'is_ac' => true,
                'is_active' => true,
            ]
        );

        $layoutService = app(SeatLayoutService::class);
        $rowDefs = array_fill(0, 10, ['left' => 2, 'right' => 2, 'left_type' => 'normal', 'right_type' => 'normal']);
        $built = $layoutService->buildLayout($rowDefs, 2500, 3500);

        $map = $vehicle->seatMaps()->where('is_active', true)->first();

        if (! $map) {
            $map = SeatMap::create([
                'vehicle_id' => $vehicle->id,
                'rows' => $built['rows'],
                'columns' => $built['columns'],
                'layout' => $built['layout'],
                'is_active' => true,
            ]);
            $layoutService->createSeats($map, $built['seat_definitions']);
        } elseif (empty($map->layout) || ($map->layout['type'] ?? '') !== 'row_aisle') {
            $map->seats()->delete();
            $map->update([
                'rows' => $built['rows'],
                'columns' => $built['columns'],
                'layout' => $built['layout'],
            ]);
            $layoutService->createSeats($map, $built['seat_definitions']);
        }

        $route = Route::firstOrCreate(
            [
                'bus_stand_id' => $stand->id,
                'departure_city' => 'Karachi',
                'destination_city' => 'Lahore',
            ],
            [
                'name' => 'Karachi - Lahore Express',
                'distance_km' => 1210,
                'duration_minutes' => 840,
                'base_fare' => 3500,
                'is_active' => true,
            ]
        );

        Schedule::firstOrCreate(
            [
                'route_id' => $route->id,
                'vehicle_id' => $vehicle->id,
                'departure_date' => today()->addDay(),
                'departure_time' => '22:00:00',
            ],
            [
                'fare' => 3500,
                'available_seats' => 40,
                'status' => 'scheduled',
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percentage',
                'value' => 10,
                'min_amount' => 1000,
                'max_uses' => 100,
                'is_active' => true,
                'expires_at' => now()->addMonths(3),
            ]
        );
    }
}
