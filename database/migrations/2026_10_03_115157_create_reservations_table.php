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
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('parking_slot_id')->constrained('parking_slots')->onDelete('cascade');
        $table->enum('status', ['reserved', 'occupied', 'completed', 'cancelled'])->default('reserved');
        $table->timestamp('reserved_at');
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
