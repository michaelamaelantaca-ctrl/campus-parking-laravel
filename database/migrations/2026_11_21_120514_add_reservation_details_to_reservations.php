<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (! Schema::hasColumn('reservations', 'plate_number')) {
                $table->string('plate_number', 30)->nullable()->after('parking_slot_id');
            }

            if (! Schema::hasColumn('reservations', 'vehicle_type')) {
                $table->string('vehicle_type', 100)->nullable()->after('plate_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'vehicle_type')) {
                $table->dropColumn('vehicle_type');
            }

            if (Schema::hasColumn('reservations', 'plate_number')) {
                $table->dropColumn('plate_number');
            }
        });
    }
};
