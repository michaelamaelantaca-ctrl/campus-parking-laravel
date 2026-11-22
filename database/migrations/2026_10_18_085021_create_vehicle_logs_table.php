<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_logs', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number');
            $table->string('vehicle_type')->nullable();
            $table->string('driver_name')->nullable();
            $table->dateTime('time_in');
            $table->dateTime('time_out')->nullable();
            $table->string('status')->default('Checked-In');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_logs');
    }
};