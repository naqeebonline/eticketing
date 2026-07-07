<?php

namespace App\Services\Vehicle;

use App\Models\Conductor;
use App\Models\Driver;
use App\Models\SeatMap;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VehicleRegistrationService
{
    public function __construct(
        private SeatLayoutService $seatLayoutService,
    ) {}

    /**
     * @param  array<int, array{name: string, phone?: string|null, cnic?: string|null}>  $conductors
     * @param  array<int, array{left: int, right: int}>  $seatRows
     */
    public function register(
        array $vehicleData,
        array $driverData,
        array $ownerData,
        array $conductors,
        array $seatRows,
        float $normalFare,
        float $luxuryFare,
    ): Vehicle {
        $layoutData = $this->seatLayoutService->buildLayout($seatRows, $normalFare, $luxuryFare);

        if ($layoutData['total_seats'] < 1) {
            throw ValidationException::withMessages([
                'seat_rows' => ['Add at least one row with seats.'],
            ]);
        }

        return DB::transaction(function () use ($vehicleData, $driverData, $ownerData, $conductors, $layoutData) {
            $busStandId = $vehicleData['bus_stand_id'];

            $driver = $this->createDriver($busStandId, $driverData);

            $vehicle = Vehicle::create([
                ...$vehicleData,
                'driver_id' => $driver->id,
                'owner_name' => $ownerData['name'],
                'owner_phone' => $ownerData['phone'] ?? null,
                'total_seats' => $layoutData['total_seats'],
            ]);

            $conductorIds = [];
            foreach ($conductors as $index => $conductorData) {
                $conductor = $this->createConductor($busStandId, $conductorData);
                $conductorIds[$conductor->id] = ['is_primary' => $index === 0];
            }

            $vehicle->conductors()->sync($conductorIds);

            $seatMap = SeatMap::create([
                'vehicle_id' => $vehicle->id,
                'rows' => $layoutData['rows'],
                'columns' => $layoutData['columns'],
                'layout' => $layoutData['layout'],
            ]);

            $this->seatLayoutService->createSeats($seatMap, $layoutData['seat_definitions']);

            return $vehicle->load(['driver', 'conductors', 'busStand']);
        });
    }

    private function createDriver(int $busStandId, array $data): Driver
    {
        return Driver::create([
            'bus_stand_id' => $busStandId,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'license_number' => $data['license_number'] ?? 'LIC-'.strtoupper(Str::random(8)),
            'license_expiry' => $data['license_expiry'] ?? now()->addYears(5),
            'license_class' => $data['license_class'] ?? null,
            'is_active' => true,
        ]);
    }

    private function createConductor(int $busStandId, array $data): Conductor
    {
        return Conductor::create([
            'bus_stand_id' => $busStandId,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'is_active' => true,
        ]);
    }
}
