<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_name',
        'additional_fee',
        'description',
    ];

    protected $casts = [
        'additional_fee' => 'float',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
