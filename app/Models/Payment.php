<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_ref',
        'payment_stage',
        'payment_type',
        'payment_method',
        'va_number',
        'biller_code',
        'bill_key',
        'qr_url',
        'qr_string',
        'deeplink_url',
        'gross_amount',
        'payment_status',
        'snap_token',
        'expires_at',
        'payload_response',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'payload_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
