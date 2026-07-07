<?php

use App\Models\BusStand;
use App\Models\Terminal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bus_stands', function (Blueprint $table) {
            $table->foreignId('terminal_id')->nullable()->after('owner_id')->constrained()->cascadeOnDelete();
        });

        BusStand::query()->whereNull('terminal_id')->each(function (BusStand $stand) {
            $terminal = Terminal::query()->firstOrCreate(
                ['slug' => 'karachi-saddar-terminal'],
                [
                    'name' => 'Saddar Bus Terminal',
                    'city' => $stand->city ?: 'Karachi',
                    'address' => 'Saddar, Karachi',
                    'is_active' => true,
                    'sort_order' => 1,
                ]
            );

            $stand->update(['terminal_id' => $terminal->id]);
        });
    }

    public function down(): void
    {
        Schema::table('bus_stands', function (Blueprint $table) {
            $table->dropConstrainedForeignId('terminal_id');
        });
    }
};
