<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_stand_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_stand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['bus_stand_id', 'user_id']);
        });

        if (Schema::hasColumn('bus_stands', 'owner_id')) {
            $now = now();
            foreach (DB::table('bus_stands')->whereNotNull('owner_id')->get(['id', 'owner_id']) as $stand) {
                DB::table('bus_stand_user')->insertOrIgnore([
                    'bus_stand_id' => $stand->id,
                    'user_id' => $stand->owner_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_stand_user');
    }
};
