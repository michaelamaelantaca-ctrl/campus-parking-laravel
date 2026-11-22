<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_plate',
        'parking_zone_id',
        'guard_id',
        'time_in',
        'time_out',
        'status',
    ];

    /**
     * Relationship: A log belongs to a parking zone.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'parking_zone_id');
    }

    /**
     * Relationship: A log belongs to a guard (User).
     */
    public function securityGuard(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guard_id');
    }
}