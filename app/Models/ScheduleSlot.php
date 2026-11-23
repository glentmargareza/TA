<?php

namespace App\Models;

use App\Services\SlotAvailabilityService;
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

    protected static function booted()
    {
        static::saved(function () {
            SlotAvailabilityService::clearCache();
        });

        static::deleted(function () {
            SlotAvailabilityService::clearCache();
        });
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'slot_id');
    }

    /**
     * Menghitung total armada jeep yang telah dipesan pada slot ini untuk tanggal tertentu.
     */
    public function bookedJeepsCount(string $tripDate, ?int $excludeBookingId = null): int
    {
        return SlotAvailabilityService::getBookedJeeps($this->id, $tripDate, $excludeBookingId);
    }

    /**
     * Memeriksa ketersediaan kuota armada jeep pada slot ini untuk tanggal tertentu.
     */
    public function isAvailableFor(string $tripDate, int $requiredJeeps = 1, ?int $excludeBookingId = null): bool
    {
        return SlotAvailabilityService::isSlotAvailable($this->id, $tripDate, $requiredJeeps, $excludeBookingId);
    }

    /**
     * Menghitung sisa armada jeep yang masih bisa dipesan pada slot ini.
     */
    public function remainingJeepsFor(string $tripDate, ?int $excludeBookingId = null): int
    {
        return SlotAvailabilityService::getRemainingJeeps($this->id, $tripDate, $excludeBookingId);
    }
}
