<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Karachi',
            'Lahore',
            'Islamabad',
            'Rawalpindi',
            'Multan',
            'Peshawar',
            'Faisalabad',
            'Quetta',
            'Hyderabad',
            'Sialkot',
            'Gujranwala',
            'Bahawalpur',
        ];

        foreach ($cities as $index => $name) {
            City::query()->updateOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $index + 1]
            );
        }
    }
}
