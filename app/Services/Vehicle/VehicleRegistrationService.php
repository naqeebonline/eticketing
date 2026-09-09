<?php

namespace App\Services\Vehicle;

use App\Models\Conductor;
use App\Models\Driver;
use App\Models\Seat;
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

    /**
     * Update vehicle details, staff, and seat map.
     * Never assigns or changes login users (driver/conductor user_id).
     *
     * @param  array<int, array{id?: int|null, name: string, phone?: string|null, cnic?: string|null}>  $conductors
     * @param  array<int, array{left: int, right: int}>  $seatRows
     */
    public function update(
        Vehicle $vehicle,
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

        return DB::transaction(function () use ($vehicle, $vehicleData, $driverData, $ownerData, $conductors, $layoutData) {
            $busStandId = (int) $vehicleData['bus_stand_id'];

            $this->upsertDriver($vehicle, $busStandId, $driverData);

            $vehicle->update([
                'bus_stand_id' => $busStandId,
                'vehicle_category_id' => $vehicleData['vehicle_category_id'] ?? null,
                'name' => $vehicleData['name'],
                'bus_number' => $vehicleData['bus_number'],
                'registration_number' => $vehicleData['registration_number'],
                'bus_type' => $vehicleData['bus_type'],
                'is_ac' => $vehicleData['is_ac'] ?? false,
                'luxury_type' => $vehicleData['luxury_type'] ?? null,
                'is_active' => $vehicleData['is_active'] ?? $vehicle->is_active,
                'owner_name' => $ownerData['name'],
                'owner_phone' => $ownerData['phone'] ?? null,
                'total_seats' => $layoutData['total_seats'],
            ]);

            $this->syncConductors($vehicle, $busStandId, $conductors);
            $this->syncSeatMap($vehicle, $layoutData);

            return $vehicle->fresh(['driver', 'conductors', 'busStand', 'seatMaps.seats']);
        });
    }

    /** @return array{seat_rows: list<array<string, mixed>>, normal_fare: float, luxury_fare: float} */
    public function formSeatData(Vehicle $vehicle): array
    {
        $seatMap = $vehicle->activeSeatMap() ?? $vehicle->seatMaps()->latest('id')->first();
        $layout = $seatMap?->layout ?? [];
        $rows = $layout['rows'] ?? [];

        if ($rows === []) {
            $default = array_map(
                fn () => ['left' => 2, 'right' => 2, 'left_type' => 'normal', 'right_type' => 'normal'],
                range(1, 10)
            );
            $default[0] = ['left' => 1, 'right' => 1, 'left_type' => 'normal', 'right_type' => 'normal'];

            return [
                'seat_rows' => $default,
                'normal_fare' => 2000.0,
                'luxury_fare' => 3500.0,
            ];
        }

        $seatRows = [];
        foreach ($rows as $row) {
            $legacy = ($row['row_type'] ?? 'normal') === 'luxury' ? 'luxury' : 'normal';
            $seatRows[] = [
                'left' => (int) ($row['left'] ?? 0),
                'right' => (int) ($row['right'] ?? 0),
                'left_type' => (($row['left_type'] ?? $legacy) === 'luxury') ? 'luxury' : 'normal',
                'right_type' => (($row['right_type'] ?? $legacy) === 'luxury') ? 'luxury' : 'normal',
            ];
        }

        return [
            'seat_rows' => $seatRows,
            'normal_fare' => (float) ($layout['normal_fare'] ?? 2000),
            'luxury_fare' => (float) ($layout['luxury_fare'] ?? 3500),
        ];
    }

    private function upsertDriver(Vehicle $vehicle, int $busStandId, array $data): void
    {
        if ($vehicle->driver) {
            $driver = $vehicle->driver;
            $driver->fill([
                'bus_stand_id' => $busStandId,
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'cnic' => $data['cnic'] ?? null,
            ]);

            if (! empty($data['license_number'])) {
                $driver->license_number = $data['license_number'];
            }

            $driver->save();

            return;
        }

        $driver = $this->createDriver($busStandId, $data);
        $vehicle->update(['driver_id' => $driver->id]);
    }

    /**
     * @param  array<int, array{id?: int|null, name: string, phone?: string|null, cnic?: string|null}>  $conductors
     */
    private function syncConductors(Vehicle $vehicle, int $busStandId, array $conductors): void
    {
        $syncIds = [];
        $attachedIds = $vehicle->conductors()->pluck('conductors.id')->all();

        foreach (array_values($conductors) as $index => $conductorData) {
            $name = trim((string) ($conductorData['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $conductorId = ! empty($conductorData['id']) ? (int) $conductorData['id'] : null;
            $conductor = null;

            if ($conductorId) {
                $conductor = Conductor::query()
                    ->whereKey($conductorId)
                    ->where(function ($query) use ($busStandId, $attachedIds) {
                        $query->where('bus_stand_id', $busStandId);

                        if ($attachedIds !== []) {
                            $query->orWhereIn('id', $attachedIds);
                        }
                    })
                    ->first();
            }

            if ($conductor) {
                $conductor->update([
                    'bus_stand_id' => $busStandId,
                    'name' => $name,
                    'phone' => $conductorData['phone'] ?? null,
                    'cnic' => $conductorData['cnic'] ?? null,
                ]);
            } else {
                $conductor = $this->createConductor($busStandId, [
                    'name' => $name,
                    'phone' => $conductorData['phone'] ?? null,
                    'cnic' => $conductorData['cnic'] ?? null,
                ]);
            }

            $syncIds[$conductor->id] = ['is_primary' => $index === 0];
        }

        $vehicle->conductors()->sync($syncIds);
    }

    /** @param  array{layout: array, rows: int, columns: int, total_seats: int, seat_definitions: list<array<string, mixed>>}  $layoutData */
    private function syncSeatMap(Vehicle $vehicle, array $layoutData): void
    {
        $seatMap = $vehicle->activeSeatMap() ?? $vehicle->seatMaps()->latest('id')->first();

        if (! $seatMap) {
            $seatMap = SeatMap::create([
                'vehicle_id' => $vehicle->id,
                'rows' => $layoutData['rows'],
                'columns' => $layoutData['columns'],
                'layout' => $layoutData['layout'],
                'is_active' => true,
            ]);
            $this->seatLayoutService->createSeats($seatMap, $layoutData['seat_definitions']);

            return;
        }

        $hasBookedSeats = Seat::query()
            ->where('seat_map_id', $seatMap->id)
            ->where(function ($query) {
                $query->whereHas('bookingPassengers')
                    ->orWhereHas('holds');
            })
            ->exists();

        if ($hasBookedSeats) {
            $existingCount = $seatMap->seats()->count();

            if ($existingCount !== $layoutData['total_seats']) {
                throw ValidationException::withMessages([
                    'seat_rows' => ['Seat map cannot change while seats have bookings or holds. Keep the same seat count, or clear bookings first.'],
                ]);
            }

            $seatMap->update([
                'rows' => $layoutData['rows'],
                'columns' => $layoutData['columns'],
                'layout' => $layoutData['layout'],
            ]);

            $seats = $seatMap->seats()->orderBy('id')->get();
            foreach ($layoutData['seat_definitions'] as $index => $def) {
                $seat = $seats->get($index);
                if (! $seat) {
                    continue;
                }

                $seat->update([
                    'seat_number' => $def['seat_number'],
                    'row' => $def['row'],
                    'column' => $def['column'],
                    'type' => $def['type'] ?? 'normal',
                    'fare_amount' => $def['fare_amount'] ?? null,
                ]);
            }

            return;
        }

        $seatMap->seats()->delete();
        $seatMap->update([
            'rows' => $layoutData['rows'],
            'columns' => $layoutData['columns'],
            'layout' => $layoutData['layout'],
            'is_active' => true,
        ]);
        $this->seatLayoutService->createSeats($seatMap, $layoutData['seat_definitions']);
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
