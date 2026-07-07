<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->date('departure_date')->index();
            $table->time('departure_time');
            $table->time('arrival_time')->nullable();
            $table->decimal('fare', 10, 2);
            $table->unsignedSmallInteger('available_seats');
            $table->string('status')->default('scheduled'); // scheduled, boarding, departed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['route_id', 'departure_date', 'departure_time']);
            $table->index(['vehicle_id', 'departure_date']);
        });

        Schema::create('schedule_conductor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conductor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['schedule_id', 'conductor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_conductor');
        Schema::dropIfExists('schedules');
    }
};
