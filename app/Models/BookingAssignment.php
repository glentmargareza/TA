<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'jeep_id',
        'driver_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function jeep()
    {
        return $this->belongsTo(Jeep::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
