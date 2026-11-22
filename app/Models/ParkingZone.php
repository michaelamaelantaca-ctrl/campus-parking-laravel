<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_capacity',
        'available_slots',
        'is_active',
        'location_description',
        'landmark',
        'map_x',
        'map_y',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: A parking zone has many slots.
     */
    public function slots(): HasMany
    {
        return $this->hasMany(ParkingSlot::class);
    }

    /**
     * Relationship: A parking zone has many logs.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ParkingLog::class);
    }
}
