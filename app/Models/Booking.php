<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'package_id',
        'slot_id',
        'trip_date',
        'jeep_quantity',
        'passenger_count',
        'arrival_type',
        'pickup_zone_id',
        'pickup_address',
        'package_subtotal',
        'pickup_fee',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_mode',
        'booking_status',
        'lock_expires_at',
        'snap_token',
    ];

    
    protected $casts = [
        'trip_date' => 'date',
        'package_subtotal' => 'float',
        'pickup_fee' => 'float',
        'total_amount' => 'float',
        'paid_amount' => 'float',
        'remaining_amount' => 'float',
        'lock_expires_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function slot()
    {
        return $this->belongsTo(ScheduleSlot::class, 'slot_id');
    }

    public function pickupZone()
    {
        return $this->belongsTo(PickupZone::class, 'pickup_zone_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function assignments()
    {
        return $this->hasMany(BookingAssignment::class);
    }
}
