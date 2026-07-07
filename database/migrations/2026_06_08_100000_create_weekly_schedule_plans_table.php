<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_schedule_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('fare', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['route_id', 'vehicle_id']);
        });

        Schema::create('weekly_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_schedule_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 1=Mon … 7=Sun (ISO)
            $table->time('departure_time');
            $table->time('arrival_time')->nullable();
            $table->timestamps();

            $table->unique(['weekly_schedule_plan_id', 'day_of_week']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('weekly_schedule_plan_id')
                ->nullable()
                ->after('driver_id')
                ->constrained('weekly_schedule_plans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('weekly_schedule_plan_id');
        });

        Schema::dropIfExists('weekly_schedule_days');
        Schema::dropIfExists('weekly_schedule_plans');
    }
};
