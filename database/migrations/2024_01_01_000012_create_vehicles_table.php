<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bus_stand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('bus_number')->index();
            $table->string('registration_number')->unique();
            $table->unsignedSmallInteger('total_seats');
            $table->string('bus_type'); // standard, luxury, etc.
            $table->boolean('is_ac')->default(false);
            $table->string('luxury_type')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->json('amenities')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle_conductor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conductor_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['vehicle_id', 'conductor_id']);
        });

        Schema::create('seat_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Default');
            $table->unsignedTinyInteger('rows');
            $table->unsignedTinyInteger('columns');
            $table->json('layout'); // seat configuration
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_map_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number');
            $table->unsignedTinyInteger('row');
            $table->unsignedTinyInteger('column');
            $table->string('type')->default('seater'); // seater, sleeper, vip, male, female
            $table->decimal('fare_multiplier', 4, 2)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['seat_map_id', 'seat_number']);
        });

        Schema::create('vehicle_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('maintenance_date');
            $table->date('next_due_date')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_logs');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seat_maps');
        Schema::dropIfExists('vehicle_conductor');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_categories');
    }
};
