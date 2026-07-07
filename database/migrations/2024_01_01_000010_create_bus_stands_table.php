<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_stands', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // company, individual
            $table->string('slug')->unique();
            $table->text('address');
            $table->string('city')->index();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('logo')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bus_stand_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_stand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('designation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['bus_stand_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_stand_staff');
        Schema::dropIfExists('bus_stands');
    }
};
