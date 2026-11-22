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
            if (! Schema::hasColumn('parking_zones', 'location_description')) {
                $table->string('location_description')->nullable()->after('name');
            }
            if (! Schema::hasColumn('parking_zones', 'landmark')) {
                $table->string('landmark')->nullable()->after('location_description');
            }
            if (! Schema::hasColumn('parking_zones', 'map_x')) {
                $table->decimal('map_x', 5, 2)->nullable()->after('landmark');
            }
            if (! Schema::hasColumn('parking_zones', 'map_y')) {
                $table->decimal('map_y', 5, 2)->nullable()->after('map_x');
            }
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            if (! Schema::hasColumn('parking_slots', 'row_label')) {
                $table->string('row_label', 100)->nullable()->after('slot_number');
            }
            if (! Schema::hasColumn('parking_slots', 'position_order')) {
                $table->unsignedInteger('position_order')->default(0)->after('row_label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_slots', function (Blueprint $table) {
            if (Schema::hasColumn('parking_slots', 'position_order')) {
                $table->dropColumn('position_order');
            }
            if (Schema::hasColumn('parking_slots', 'row_label')) {
                $table->dropColumn('row_label');
            }
        });

        Schema::table('parking_zones', function (Blueprint $table) {
            foreach (['map_y', 'map_x', 'landmark', 'location_description'] as $column) {
                if (Schema::hasColumn('parking_zones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
