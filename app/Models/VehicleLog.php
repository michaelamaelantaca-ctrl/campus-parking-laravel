<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLog extends Model
{
    use HasFactory;

    protected $table = 'vehicle_logs';

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'driver_name',
        'parking_zone_id',
        'parking_slot_id',
        'guard_id',
        'time_in',
        'time_out',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'time_in' => 'datetime',
            'time_out' => 'datetime',
        ];
    }

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'parking_zone_id');
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'parking_slot_id');
    }

    public function securityGuard()
    {
        return $this->belongsTo(User::class, 'guard_id');
    }
}
