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
            $table->string('from_city')->nullable()->after('city')->index();
            $table->string('to_city')->nullable()->after('from_city')->index();
        });

        BusStand::query()->with('terminal')->each(function (BusStand $stand) {
            $from = $stand->terminal?->city ?? $stand->city;
            $to = $stand->to_city;
            if (! $to || $to === $from) {
                $to = 'Lahore';
            }
            $stand->update([
                'from_city' => $from,
                'to_city' => $to,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('bus_stands', function (Blueprint $table) {
            $table->dropColumn(['from_city', 'to_city']);
        });
    }
};
