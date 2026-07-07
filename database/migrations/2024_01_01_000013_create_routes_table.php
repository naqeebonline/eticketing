<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('bus_stand_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('departure_city')->index();
            $table->string('destination_city')->index();
            $table->string('name');
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->decimal('base_fare', 10, 2);
            $table->json('map_polyline')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedSmallInteger('order');
            $table->unsignedInteger('arrival_offset_minutes')->default(0);
            $table->decimal('fare_from_origin', 10, 2)->nullable();
            $table->timestamps();
            $table->index(['route_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_stops');
        Schema::dropIfExists('routes');
    }
};
