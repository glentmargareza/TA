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
        $requiredJeeps = max(1, (int) $booking->jeep_quantity);

        // Jika sudah ditugaskan lengkap dan tidak dipaksa, pertahankan penugasan
        if ($force) {
            $booking->assignments()->delete();
        } elseif ($booking->assignments()->count() >= $requiredJeeps) {
            return $booking->assignments()->first();
        }

        return DB::transaction(function () use ($booking, $requiredJeeps) {
            $existing = $booking->assignments()->get();
            $assignedJeepIds = $existing->pluck('jeep_id')->filter()->all();
            $assignedDriverIds = $existing->pluck('driver_id')->filter()->all();

            $needed = $requiredJeeps - count($assignedJeepIds);
            if ($needed <= 0) {
                return $existing->first();
            }

            // 1. Pilih armada Jeep: prioritaskan yang READY dan belum ditugaskan untuk booking ini
            $availableJeeps = Jeep::where('status', 'READY')
                ->whereNotIn('id', $assignedJeepIds)
                ->withCount('assignments')
                ->orderBy('assignments_count', 'asc')
                ->limit($needed)
                ->get();

            if ($availableJeeps->count() < $needed) {
                $moreNeeded = $needed - $availableJeeps->count();
                $exclude = array_merge($assignedJeepIds, $availableJeeps->pluck('id')->all());
                $fallbackJeeps = Jeep::where('status', '!=', 'MAINTENANCE')
                    ->whereNotIn('id', $exclude)
                    ->limit($moreNeeded)
                    ->get();
                $availableJeeps = $availableJeeps->merge($fallbackJeeps);
            }

            // 2. Pilih Driver: prioritaskan driver aktif yang belum ditugaskan untuk booking ini
            $availableDrivers = Driver::where('is_active', true)
                ->whereNotIn('id', $assignedDriverIds)
                ->withCount('assignments')
                ->orderBy('assignments_count', 'asc')
                ->limit($needed)
                ->get();

            if ($availableDrivers->count() < $needed) {
                $moreNeeded = $needed - $availableDrivers->count();
                $exclude = array_merge($assignedDriverIds, $availableDrivers->pluck('id')->all());
                $fallbackDrivers = Driver::whereNotIn('id', $exclude)
                    ->limit($moreNeeded)
                    ->get();
                $availableDrivers = $availableDrivers->merge($fallbackDrivers);
            }

            $count = min($availableJeeps->count(), $availableDrivers->count());
            if ($count === 0 && $existing->isEmpty()) {
                Log::warning("AutoAssign: Data Jeep atau Driver tidak tersedia di database untuk booking #{$booking->booking_code}.");
                return null;
            }

            $firstAssignment = $existing->first();
            $newJeepNames = [];
            $newDriverNames = [];

            for ($i = 0; $i < $count; $i++) {
                $j = $availableJeeps[$i];
                $d = $availableDrivers[$i];

                $assignment = BookingAssignment::create([
                    'booking_id'  => $booking->id,
                    'jeep_id'     => $j->id,
                    'driver_id'   => $d->id,
                    'assigned_at' => now('Asia/Jakarta'),
                ]);

                if ($booking->trip_date && $booking->trip_date->isToday()) {
                    $j->update(['status' => 'ON_TRIP']);
                }

                $newJeepNames[] = "{$j->model_name} [{$j->plate_number}]";
                $newDriverNames[] = $d->full_name;

                if (!$firstAssignment) {
                    $firstAssignment = $assignment;
                }
            }

            // Catat ke riwayat aktivitas operasional
            if (!empty($newJeepNames)) {
                try {
                    $jeepsText = implode(', ', $newJeepNames);
                    $driversText = implode(', ', $newDriverNames);
                    ActivityLog::create([
                        'user_id' => null,
                        'type' => 'AUTO_ASSIGN',
                        'title' => 'Alokasi Otomatis Driver & Armada 🚙',
                        'message' => "Sistem otomatis menugaskan armada {$jeepsText} dan Driver {$driversText} untuk booking #{$booking->booking_code} ({$booking->customer_name}).",
                        'icon_type' => 'admin',
                        'link' => route('admin.bookings', ['search' => $booking->booking_code]),
                        'is_read' => false,
                        'metadata' => [
                            'booking_id' => $booking->id,
                            'booking_code' => $booking->booking_code,
                            'customer_name' => $booking->customer_name,
                            'customer_notes' => $booking->customer_notes,
                        ],
                    ]);
                } catch (\Throwable $e) {
                    Log::info("AutoAssign ActivityLog note: " . $e->getMessage());
                }
            }

            return $firstAssignment;
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

