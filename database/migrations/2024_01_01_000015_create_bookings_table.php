<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('pending')->index();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->unsignedInteger('loyalty_points_used')->default(0);
            $table->unsignedInteger('loyalty_points_earned')->default(0);
            $table->string('booking_source')->default('online'); // online, offline, api
            $table->foreignId('booked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('qr_code')->nullable();
            $table->timestamp('hold_expires_at')->nullable()->index();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['schedule_id', 'status']);
        });

        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('cnic')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('gender'); // male, female
            $table->string('passenger_type')->default('adult'); // adult, child
            $table->decimal('fare', 10, 2);
            $table->timestamps();
        });

        Schema::create('seat_holds', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->index();
            $table->timestamps();
            $table->unique(['schedule_id', 'seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_holds');
        Schema::dropIfExists('booking_passengers');
        Schema::dropIfExists('bookings');
    }
};
