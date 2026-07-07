<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bus_stand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('license_number')->index();
            $table->date('license_expiry');
            $table->string('license_class')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('conductors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bus_stand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('conductor_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conductor_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['conductor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conductor_attendance');
        Schema::dropIfExists('conductors');
        Schema::dropIfExists('drivers');
    }
};
