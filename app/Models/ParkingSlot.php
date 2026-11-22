<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_zone_id',
        'slot_number',
        'status',
        'is_occupied',
        'row_label',
        'position_order',
    ];

    /**
     * Get the zone that owns the parking slot.
     */
    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'parking_zone_id');
    }

    /**
     * Get the reservation associated with the parking slot.
     */
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'parking_slot_id')
            ->where('status', 'reserved')
            ->latest('reserved_at');
    }
}
