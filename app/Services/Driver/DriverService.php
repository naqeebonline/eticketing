<?php

namespace App\Services\Driver;

use App\Models\Driver;
use Illuminate\Support\Str;

class DriverService
{
    public function create(array $data): Driver
    {
        return Driver::create([
            'bus_stand_id' => $data['bus_stand_id'],
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'license_number' => $data['license_number'] ?? 'LIC-'.strtoupper(Str::random(8)),
            'license_expiry' => $data['license_expiry'],
            'license_class' => $data['license_class'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(Driver $driver, array $data): Driver
    {
        $driver->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'license_number' => $data['license_number'],
            'license_expiry' => $data['license_expiry'],
            'license_class' => $data['license_class'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $driver->fresh();
    }
}
