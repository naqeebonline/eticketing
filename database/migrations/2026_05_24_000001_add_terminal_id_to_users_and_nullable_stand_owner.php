<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('terminal_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('bus_stands', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
        });

        Schema::table('bus_stands', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->change();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bus_stands', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
        });

        Schema::table('bus_stands', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable(false)->change();
            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('terminal_id');
        });
    }
};
