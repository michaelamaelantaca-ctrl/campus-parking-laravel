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
        Schema::table('parking_zones', function (Blueprint $table) {
            if (! Schema::hasColumn('parking_zones', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('available_slots');
            }
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            if (! Schema::hasColumn('parking_slots', 'status')) {
                $table->string('status', 20)->default('available')->after('slot_number');
            }
        });

        Schema::table('vehicle_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicle_logs', 'parking_zone_id')) {
                $table->foreignId('parking_zone_id')->nullable()->after('vehicle_type')->constrained('parking_zones')->nullOnDelete();
            }
            if (! Schema::hasColumn('vehicle_logs', 'parking_slot_id')) {
                $table->foreignId('parking_slot_id')->nullable()->after('parking_zone_id')->constrained('parking_slots')->nullOnDelete();
            }
            if (! Schema::hasColumn('vehicle_logs', 'guard_id')) {
                $table->foreignId('guard_id')->nullable()->after('parking_slot_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_logs', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_logs', 'guard_id')) {
                $table->dropConstrainedForeignId('guard_id');
            }
            if (Schema::hasColumn('vehicle_logs', 'parking_slot_id')) {
                $table->dropConstrainedForeignId('parking_slot_id');
            }
            if (Schema::hasColumn('vehicle_logs', 'parking_zone_id')) {
                $table->dropConstrainedForeignId('parking_zone_id');
            }
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            if (Schema::hasColumn('parking_slots', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('parking_zones', function (Blueprint $table) {
            if (Schema::hasColumn('parking_zones', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
