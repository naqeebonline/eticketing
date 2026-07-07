<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('driver_id');
            $table->string('owner_phone')->nullable()->after('owner_name');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->string('name')->nullable()->after('bus_stand_id');
            $table->string('phone')->nullable()->after('name');
            $table->string('cnic')->nullable()->after('phone');
        });

        Schema::table('conductors', function (Blueprint $table) {
            $table->string('name')->nullable()->after('bus_stand_id');
            $table->string('phone')->nullable()->after('name');
            $table->string('cnic')->nullable()->after('phone');
        });

        $this->nullableUserId('drivers');
        $this->nullableUserId('conductors');
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'owner_phone']);
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'cnic']);
        });

        Schema::table('conductors', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'cnic']);
        });
    }

    private function nullableUserId(string $table): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $fk = "{$table}_user_id_foreign";
        DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$fk}");
        DB::statement("ALTER TABLE {$table} MODIFY user_id BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$fk} FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
    }
};
