<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingAssignment;
use App\Models\Driver;
use App\Models\Jeep;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingAssignmentService
{
    /**
     * Otomatis alokasikan Driver dan Armada Jeep untuk booking yang telah terbayar (DP atau Lunas).
     * Menerapkan prinsip rotasi adil paguyuban:
     * - Driver aktif yang memiliki jumlah penugasan terendah.
     * - Armada Jeep yang berstatus READY dan memiliki riwayat tugas terendah/seimbang.
     *
     * @param Booking $booking
     * @param bool $force Jika true, timpa penugasan sebelumnya
     * @return BookingAssignment|null
     */
    public static function autoAssign(Booking $booking, bool $force = false): ?BookingAssignment
    {
        // Jika sudah ditugaskan dan tidak dipaksa (misal sudah dipilih manual oleh admin), jangan ubah
        if (!$force && $booking->assignments()->exists()) {
            return $booking->assignments()->first();
        }

        return DB::transaction(function () use ($booking) {
            // 1. Pilih armada Jeep: prioritaskan yang READY dan distribusi tugas seimbang
            $jeep = Jeep::where('status', 'READY')
                ->withCount('assignments')
                ->orderBy('assignments_count', 'asc')
                ->inRandomOrder()
                ->first()
                ?? Jeep::inRandomOrder()->first();

            // 2. Pilih Driver: prioritaskan driver aktif dengan penugasan tersedikit (rotasi adil paguyuban)
            $driver = Driver::where('is_active', true)
                ->withCount('assignments')
                ->orderBy('assignments_count', 'asc')
                ->inRandomOrder()
                ->first()
                ?? Driver::first();

            if (!$jeep || !$driver) {
                Log::warning("AutoAssign: Data Jeep atau Driver tidak tersedia di database untuk booking #{$booking->booking_code}.");
                return null;
            }

            $assignment = BookingAssignment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'jeep_id' => $jeep->id,
                    'driver_id' => $driver->id,
                    'assigned_at' => now(),
                ]
            );

            // Jika jadwal trip adalah hari ini, ubah status armada menjadi ON_TRIP
            if ($booking->trip_date && $booking->trip_date->isToday()) {
                $jeep->update(['status' => 'ON_TRIP']);
            }

            // Catat ke riwayat aktivitas operasional
            try {
                ActivityLog::create([
                    'user_id' => null,
                    'type' => 'AUTO_ASSIGN',
                    'title' => 'Alokasi Otomatis Driver & Armada 🚙',
                    'message' => "Sistem otomatis menugaskan unit {$jeep->model_name} [{$jeep->plate_number}] dan Driver {$driver->full_name} untuk booking #{$booking->booking_code} ({$booking->customer_name}).",
                    'icon_type' => 'admin',
                    'link' => route('admin.bookings', ['search' => $booking->booking_code]),
                    'is_read' => false,
                    'metadata' => [
                        'booking_id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                        'jeep_id' => $jeep->id,
                        'jeep_name' => $jeep->model_name,
                        'driver_id' => $driver->id,
                        'driver_name' => $driver->full_name,
                        'customer_name' => $booking->customer_name,
                        'customer_notes' => $booking->customer_notes,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::info("AutoAssign ActivityLog note: " . $e->getMessage());
            }

            return $assignment;
        });
    }

    /**
     * Alokasikan otomatis seluruh booking yang sudah berstatus terbayar (DP_PAID, FULL_PAID, COMPLETED)
     * namun belum memiliki alokasi armada/driver.
     *
     * @return int Jumlah booking yang berhasil dialokasikan
     */
    public static function autoAssignUnassignedPaidBookings(): int
    {
        $unassignedBookings = Booking::whereIn('booking_status', ['DP_PAID', 'FULL_PAID', 'COMPLETED'])
            ->whereDoesntHave('assignments')
            ->get();

        $count = 0;
        foreach ($unassignedBookings as $booking) {
            if (self::autoAssign($booking)) {
                $count++;
            }
        }

        return $count;
    }
}

