<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_label',
        'start_time',
        'end_time',
        'max_jeeps',
        'is_active',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'slot_id');
    }
}
