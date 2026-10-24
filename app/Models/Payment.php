<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_ref',
        'payment_stage',
        'payment_type',
        'gross_amount',
        'payment_status',
        'snap_token',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'float',
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
