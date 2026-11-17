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

    protected $casts = [
        'is_active' => 'boolean',
        'max_jeeps' => 'integer',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'slot_id');
    }

    /**
     * Menghitung total armada jeep yang telah dipesan pada slot ini untuk tanggal tertentu.
     */
    public function bookedJeepsCount(string $tripDate): int
    {
        return (int) $this->bookings()
            ->where('trip_date', $tripDate)
            ->whereIn('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID'])
            ->where(function ($q) {
                $q->whereNull('lock_expires_at')->orWhere('lock_expires_at', '>', now());
            })
            ->sum('jeep_quantity');
    }

    /**
     * Memeriksa ketersediaan kuota armada jeep pada slot ini untuk tanggal tertentu.
     */
    public function isAvailableFor(string $tripDate, int $requiredJeeps = 1): bool
    {
        $booked = $this->bookedJeepsCount($tripDate);
        return ($booked + $requiredJeeps) <= $this->max_jeeps;
    }

    /**
     * Menghitung sisa armada jeep yang masih bisa dipesan pada slot ini.
     */
    public function remainingJeepsFor(string $tripDate): int
    {
        $booked = $this->bookedJeepsCount($tripDate);
        return max(0, $this->max_jeeps - $booked);
    }
}
