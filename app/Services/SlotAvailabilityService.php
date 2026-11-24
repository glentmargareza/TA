<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Jeep;
use App\Models\Package;
use App\Models\ScheduleSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SlotAvailabilityService
{
    const CACHE_KEY_PACKAGES = 'slot_avail_active_packages';
    const CACHE_KEY_SLOTS    = 'slot_avail_active_slots';

    /**
     * Booking yang sah dan mengunci kuota:
     * - DP_PAID, FULL_PAID, WAITING_CONFIRMATION, COMPLETED: selalu mengunci.
     * - PENDING: mengunci selama lock_expires_at belum lewat (atau null).
     * - CANCELLED / EXPIRED: tidak mengunci.
     */
    public static function applyActiveBookingCondition(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereIn('booking_status', ['DP_PAID', 'FULL_PAID', 'WAITING_CONFIRMATION', 'COMPLETED'])
              ->orWhere(function ($pendingQ) {
                  $pendingQ->where('booking_status', 'PENDING')
                           ->where(function ($lockQ) {
                               $lockQ->whereNull('lock_expires_at')
                                     ->orWhere('lock_expires_at', '>', now());
                           });
              });
        });
    }

    /**
     * Jumlah jeep yang siap jalan dan aktif operasional.
     * Mengikutsertakan unit READY dan ON_TRIP (hanya MAINTENANCE yang dikecualikan).
     * Return null jika tabel/kolom jeep tidak bisa dibaca, supaya sistem
     * kembali memakai max_jeeps sesi dan tidak memblokir booking.
     */
    public static function readyJeepCapacity(): ?int
    {
        if (!Schema::hasTable('jeeps')) {
            return null;
        }

        $query = Jeep::query();

        if (Schema::hasColumn('jeeps', 'status')) {
            $query->where('status', '!=', 'MAINTENANCE');
        } elseif (Schema::hasColumn('jeeps', 'is_active')) {
            $query->where('is_active', true);
        } else {
            return null;
        }

        return (int) $query->count();
    }

    /**
     * Kuota efektif satu sesi = min(max_jeeps sesi, jumlah jeep siap jalan).
     */
    public static function slotCapacity(ScheduleSlot $slot, ?int $ready = null): int
    {
        $max = (int) $slot->max_jeeps;
        $ready ??= self::readyJeepCapacity();

        return $ready === null ? $max : min($max, $ready);
    }

    /**
     * Total armada jeep yang telah dipesan pada slot dan tanggal tertentu.
     */
    public static function getBookedJeeps(int $slotId, string $date, ?int $excludeBookingId = null): int
    {
        $query = Booking::where('slot_id', $slotId)
            ->whereDate('trip_date', $date);

        if ($excludeBookingId !== null && $excludeBookingId > 0) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return (int) self::applyActiveBookingCondition($query)->sum('jeep_quantity');
    }

    /**
     * Sisa kapasitas armada jeep pada slot dan tanggal tertentu.
     */
    public static function getRemainingJeeps(int $slotId, string $date, ?int $excludeBookingId = null): int
    {
        $slot = ScheduleSlot::find($slotId);
        if (!$slot || !$slot->is_active) {
            return 0;
        }

        $booked = self::getBookedJeeps($slotId, $date, $excludeBookingId);
        return max(0, self::slotCapacity($slot) - $booked);
    }

    public static function isSlotAvailable(int $slotId, string $date, int $requiredJeeps = 1, ?int $excludeBookingId = null): bool
    {
        return self::getRemainingJeeps($slotId, $date, $excludeBookingId) >= $requiredJeeps;
    }

    /**
     * Ketersediaan seluruh slot aktif untuk tanggal tertentu.
     * Dipakai halaman booking, panel monitoring, dan modal reschedule admin.
     */
    public static function getSlotAvailability(string $date, int $jeeps = 1, ?int $excludeBookingId = null): array
    {
        $now     = now('Asia/Jakarta');
        $today   = $now->format('Y-m-d');
        $isToday = $date === $today;
        $isPast  = $date < $today;
        $nowTime = $now->format('H:i');

        $ready = self::readyJeepCapacity();

        $slots = ScheduleSlot::where('is_active', true)
            ->orderBy('start_time')
            ->get();

        $slotData = $slots->map(function ($s) use ($date, $isToday, $isPast, $nowTime, $jeeps, $excludeBookingId, $ready) {
            $time       = substr((string) $s->start_time, 0, 5);
            $label      = trim((string) $s->slot_label) ?: ('Sesi ' . $s->id);
            $cleanLabel = preg_match('/\(\d{2}:\d{2}\s*WIB\)/i', $label) ? $label : ($label . ' (' . $time . ' WIB)');
            $isSunrise  = str_contains(strtolower($s->slot_label), 'sunrise') || $time === '04:30';

            $capacity  = self::slotCapacity($s, $ready);
            $booked    = self::getBookedJeeps($s->id, $date, $excludeBookingId);
            $remaining = max(0, $capacity - $booked);
            $passed    = $isPast || ($isToday && $time <= $nowTime);
            $available = !$passed && ($remaining >= $jeeps);

            return [
                'id'              => $s->id,
                'label'           => $cleanLabel,
                'slot_label'      => $s->slot_label,
                'start_time'      => $s->start_time,
                'end_time'        => $s->end_time,
                'time'            => $time,
                'time_range'      => $s->start_time . ' - ' . $s->end_time . ' WIB',
                'is_sunrise'      => $isSunrise,
                'booked_jeeps'    => $booked,
                'remaining'       => $remaining,
                'remaining_jeeps' => $remaining,
                'max_jeeps'       => $capacity,
                'passed'          => $passed,
                'available'       => $available,
                'is_full'         => $remaining <= 0,
            ];
        });

        $totalBookedJeeps = (int) $slotData->sum('booked_jeeps');

        return [
            'date'               => $date,
            'is_today'           => $isToday,
            'has_bookings'       => $totalBookedJeeps > 0,
            'total_booked_jeeps' => $totalBookedJeeps,
            'slots'              => $slotData->values()->all(),
            'month_bookings'     => self::getMonthBookings(substr($date, 0, 7)),
            'packages'           => self::getActivePackages(),
            'blocked_dates'      => [],
        ];
    }

    /**
     * Agregasi booking bulanan untuk penanda kalender visual.
     */
    public static function getMonthBookings(string $month): array
    {
        $query = Booking::where('trip_date', 'like', $month . '%');
        self::applyActiveBookingCondition($query);

        return $query->selectRaw('trip_date, SUM(jeep_quantity) as total_jeeps, COUNT(*) as total_orders')
            ->groupBy('trip_date')
            ->get()
            ->mapWithKeys(function ($item) {
                $d = is_string($item->trip_date) ? substr($item->trip_date, 0, 10) : $item->trip_date->format('Y-m-d');
                return [$d => [
                    'total_jeeps'  => (int) $item->total_jeeps,
                    'total_orders' => (int) $item->total_orders,
                ]];
            })
            ->all();
    }

    /**
     * Ringkasan kuota per hari untuk dashboard & manajemen admin.
     * Kapasitas = jumlah jeep siap jalan; terpesan dihitung dari sesi terpadat.
     */
    public static function getDayQuotaSummary(string $date): array
    {
        $totalCapacity = self::readyJeepCapacity();
        if ($totalCapacity === null || $totalCapacity <= 0) {
            $totalCapacity = (int) (ScheduleSlot::where('is_active', true)->max('max_jeeps') ?? 20);
        }

        $activeSlots = ScheduleSlot::where('is_active', true)->orderBy('start_time')->get();
        $maxBooked = 0;
        $peakLabel = null;

        foreach ($activeSlots as $slot) {
            $booked = self::getBookedJeeps($slot->id, $date);
            if ($booked > $maxBooked) {
                $maxBooked = $booked;
                $peakLabel = $slot->slot_label;
            }
        }

        $totalBooked    = min($maxBooked, $totalCapacity);
        $totalRemaining = max(0, $totalCapacity - $totalBooked);

        return [
            'date'            => $date,
            'total_capacity'  => $totalCapacity,
            'total_booked'    => $totalBooked,
            'total_remaining' => $totalRemaining,
            'peak_label'      => $maxBooked > 0 ? $peakLabel : null,
        ];
    }

    /**
     * Daftar paket aktif (cache 1 jam, di-invalidasi saat admin mengedit paket).
     */
    public static function getActivePackages(): array
    {
        return Cache::remember(self::CACHE_KEY_PACKAGES, 3600, function () {
            return Package::where('is_active', true)
                ->orderBy('id')
                ->get()
                ->map(fn ($p) => [
                    'id'         => $p->id,
                    'name'       => $p->name,
                    'slug'       => $p->slug,
                    'is_sunrise' => str_contains(strtolower($p->slug . ' ' . $p->name), 'sunrise'),
                    'price'      => (int) $p->price_per_jeep,
                    'dur'        => $p->duration ?? ($p->effective_duration_text ?? ''),
                ])
                ->values()
                ->all();
        });
    }

    /**
     * Daftar sesi aktif (cache 1 jam, di-invalidasi saat admin mengedit sesi).
     */
    public static function getActiveSlots(): array
    {
        return Cache::remember(self::CACHE_KEY_SLOTS, 3600, function () {
            return ScheduleSlot::where('is_active', true)
                ->orderBy('start_time')
                ->get()
                ->map(function ($s) {
                    $time       = substr((string) $s->start_time, 0, 5);
                    $label      = trim((string) $s->slot_label) ?: ('Sesi ' . $s->id);
                    $cleanLabel = preg_match('/\(\d{2}:\d{2}\s*WIB\)/i', $label) ? $label : ($label . ' (' . $time . ' WIB)');
                    $isSunrise  = str_contains(strtolower($s->slot_label), 'sunrise') || $time === '04:30';
                    return [
                        'id'         => $s->id,
                        'label'      => $cleanLabel,
                        'time'       => $time,
                        'max_jeeps'  => (int) $s->max_jeeps,
                        'is_sunrise' => $isSunrise,
                    ];
                })
                ->values()
                ->all();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_PACKAGES);
        Cache::forget(self::CACHE_KEY_SLOTS);
    }
}