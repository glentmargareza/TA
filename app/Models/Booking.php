<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'qr_token',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_notes',
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

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (empty($booking->qr_token)) {
                $booking->qr_token = 'WKR-QR-' . strtoupper(\Illuminate\Support\Str::random(20)) . '-' . time();
            }
        });
    }

    
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
        return $this->belongsTo(Package::class)->withTrashed();
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

    public function manualPayments()
    {
        return $this->hasMany(Payment::class)->where('payment_type', 'MANUAL_BPD_DIY');
    }

    public function latestManualPayment()
    {
        return $this->hasOne(Payment::class)->where('payment_type', 'MANUAL_BPD_DIY')->latestOfMany();
    }

    public function assignments()
    {
        return $this->hasMany(BookingAssignment::class);
    }

    /**
     * URL Bukti Transfer Pelanggan (jika ada bukti upload)
     */
    public function getProofUrlAttribute(): ?string
    {
        foreach ($this->payments as $payment) {
            if (is_array($payment->payload_response) && !empty($payment->payload_response['proof_url'])) {
                return asset($payment->payload_response['proof_url']);
            }
        }
        return null;
    }

    /**
     * Nama Pengirim Rekening / Struk Bukti Transfer
     */
    public function getSenderNameAttribute(): ?string
    {
        foreach ($this->payments as $payment) {
            if (is_array($payment->payload_response) && !empty($payment->payload_response['sender_name'])) {
                return $payment->payload_response['sender_name'];
            }
        }
        return null;
    }

    /**
     * Bank Asal Pengirim Bukti Transfer
     */
    public function getSenderBankAttribute(): ?string
    {
        foreach ($this->payments as $payment) {
            if (!empty($payment->sender_bank)) {
                return $payment->sender_bank;
            }
            if (is_array($payment->payload_response) && !empty($payment->payload_response['sender_bank'])) {
                return $payment->payload_response['sender_bank'];
            }
        }
        return null;
    }

    /**
     * Saluran / Channel Metode Pembayaran (e.g. BRI, BCA, DANA, QRIS, dll.)
     */
    public function getPaymentChannelAttribute(): string
    {
        // 1. Cek dari record pembayaran yang ada
        $payment = $this->payments->sortByDesc('id')->first();

        if ($payment) {
            // A. Cek sender_bank (Transfer manual / e-wallet pengirim)
            $senderBank = $payment->sender_bank 
                ?? ($payment->payload_response['sender_bank'] ?? null);

            if ($senderBank && !empty(trim($senderBank))) {
                $sb = trim($senderBank);
                $upper = strtoupper($sb);
                if (str_contains($upper, 'BCA')) return 'Bank BCA';
                if (str_contains($upper, 'BRI')) return 'Bank BRI';
                if (str_contains($upper, 'BNI')) return 'Bank BNI';
                if (str_contains($upper, 'MANDIRI')) return 'Bank Mandiri';
                if (str_contains($upper, 'BSI') || str_contains($upper, 'SYARIAH INDONESIA')) return 'Bank Syariah Indonesia (BSI)';
                if (str_contains($upper, 'BPD')) return 'Bank BPD DIY';
                if (str_contains($upper, 'CIMB')) return 'Bank CIMB Niaga';
                if (str_contains($upper, 'PERMATA')) return 'Bank Permata';
                if (str_contains($upper, 'DANA')) return 'DANA (E-Wallet)';
                if (str_contains($upper, 'GOPAY')) return 'GoPay (E-Wallet)';
                if (str_contains($upper, 'OVO')) return 'OVO (E-Wallet)';
                if (str_contains($upper, 'SHOPEE')) return 'ShopeePay (E-Wallet)';
                if (str_contains($upper, 'LINKAJA')) return 'LinkAja (E-Wallet)';
                if (str_contains($upper, 'QRIS')) return 'QRIS';
                return ucwords(strtolower($sb));
            }

            // B. Cek payment_method atau payment_type
            $rawMethod = strtolower($payment->payment_method ?? $payment->payment_type ?? '');

            if (str_contains($rawMethod, 'bri')) return 'Bank BRI (VA)';
            if (str_contains($rawMethod, 'bca')) return 'Bank BCA (VA)';
            if (str_contains($rawMethod, 'bni')) return 'Bank BNI (VA)';
            if (str_contains($rawMethod, 'mandiri') || str_contains($rawMethod, 'echannel')) return 'Bank Mandiri';
            if (str_contains($rawMethod, 'permata')) return 'Bank Permata (VA)';
            if (str_contains($rawMethod, 'bpd')) return 'Bank BPD DIY';
            if (str_contains($rawMethod, 'dana')) return 'DANA (E-Wallet)';
            if (str_contains($rawMethod, 'gopay')) return 'GoPay (E-Wallet)';
            if (str_contains($rawMethod, 'shopee')) return 'ShopeePay';
            if (str_contains($rawMethod, 'qris')) return 'QRIS';
            if (str_contains($rawMethod, 'cash') || str_contains($rawMethod, 'tunai')) return 'Tunai di Loket';

            // C. Cek payload_response dari payment gateway Midtrans
            if (is_array($payment->payload_response)) {
                $resp = $payment->payload_response;
                if (!empty($resp['va_numbers'][0]['bank'])) {
                    return 'Bank ' . strtoupper($resp['va_numbers'][0]['bank']) . ' (VA)';
                }
                if (!empty($resp['bank'])) {
                    return 'Bank ' . strtoupper($resp['bank']) . ' (VA)';
                }
                if (!empty($resp['payment_type'])) {
                    $pt = strtolower($resp['payment_type']);
                    if ($pt === 'qris') return 'QRIS';
                    if ($pt === 'gopay') return 'GoPay';
                    if ($pt === 'shopeepay') return 'ShopeePay';
                    if ($pt === 'bank_transfer') return 'Transfer Bank';
                }
            }

            if (!empty($payment->payment_method) && !str_starts_with($payment->payment_method, 'midtrans')) {
                return ucwords(str_replace(['_', '-'], ' ', $payment->payment_method));
            }
        }

        // Fallback berdasarkan status booking
        if (in_array($this->booking_status, ['FULL_PAID', 'DP_PAID'])) {
            return 'Transfer Bank / Midtrans';
        }

        return 'Menunggu Pembayaran';
    }
}
