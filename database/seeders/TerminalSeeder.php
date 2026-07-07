<?php

namespace Database\Seeders;

use App\Models\Terminal;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    public function run(): void
    {
        Terminal::query()->updateOrCreate(
            ['slug' => 'karachi-saddar-terminal'],
            [
                'name' => 'Saddar Bus Terminal',
                'city' => 'Karachi',
                'address' => 'Saddar, Karachi',
                'phone' => '021-111000111',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Terminal::query()->updateOrCreate(
            ['slug' => 'lahore-thokar-terminal'],
            [
                'name' => 'Thokar Niaz Baig Terminal',
                'city' => 'Lahore',
                'address' => 'Thokar Niaz Baig, Lahore',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );
    }
}
