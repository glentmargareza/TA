<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Booking;
use App\Models\User;
use App\Services\SlotAvailabilityService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuotaIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
    }

    /**
     * a) Booking baru mengurangi sisa; batal atau kedaluwarsa mengembalikannya.
     */
    public function test_new_booking_reduces_remaining_and_cancel_or_expiry_returns_it(): void
    {
        $slot = ScheduleSlot::where('is_active', true)->first();
        $date = date('Y-m-d', strtotime('+5 days'));

        $initialRemaining = SlotAvailabilityService::getRemainingJeeps($slot->id, $date);

        // 1. Buat booking PENDING aktif
        $booking = Booking::create([
            'booking_code' => 'TEST-001',
            'customer_name' => 'Tester 1',
            'customer_phone' => '081234567890',
            'package_id' => Package::first()->id,
            'slot_id' => $slot->id,
            'trip_date' => $date,
            'jeep_quantity' => 2,
            'passenger_count' => 8,
            'package_subtotal' => 900000,
            'total_amount' => 900000,
            'paid_amount' => 900000,
            'payment_mode' => 'FULL',
            'booking_status' => 'PENDING',
            'lock_expires_at' => now()->addMinutes(30),
        ]);

        $this->assertEquals($initialRemaining - 2, SlotAvailabilityService::getRemainingJeeps($slot->id, $date));

        // 2. Booking CANCELLED -> kuota kembali
        $booking->update(['booking_status' => 'CANCELLED']);
        $this->assertEquals($initialRemaining, SlotAvailabilityService::getRemainingJeeps($slot->id, $date));

        // 3. Booking PENDING yang kedaluwarsa -> kuota kembali
        $booking->update([
            'booking_status' => 'PENDING',
            'lock_expires_at' => now()->subMinutes(10),
        ]);
        $this->assertEquals($initialRemaining, SlotAvailabilityService::getRemainingJeeps($slot->id, $date));

        // 4. Booking LUNAS (FULL_PAID) -> kuota tetap terpotong
        $booking->update([
            'booking_status' => 'FULL_PAID',
            'lock_expires_at' => null,
        ]);
        $this->assertEquals($initialRemaining - 2, SlotAvailabilityService::getRemainingJeeps($slot->id, $date));
    }

    /**
     * b) Dua booking bersamaan untuk kuota terakhir: hanya satu yang berhasil.
     */
    public function test_two_simultaneous_bookings_for_last_quota_only_one_succeeds(): void
    {
        $package = Package::first();
        $slot = ScheduleSlot::where('is_active', true)
            ->where('slot_label', 'not like', '%Sunrise%')
            ->first();
        $date = date('Y-m-d', strtotime('+6 days'));

        // Set sisa kuota tinggal 1 jeep
        $currentBooked = SlotAvailabilityService::getBookedJeeps($slot->id, $date);
        $slot->update(['max_jeeps' => $currentBooked + 1]);

        $postData = [
            'package_id' => $package->id,
            'slot_id' => $slot->id,
            'trip_date' => $date,
            'passenger_count' => 4,
            'jeep_quantity' => 1,
            'arrival_type' => 'self_drive',
            'customer_name' => 'Peserta Kuota Terakhir 1',
            'customer_phone' => '081299998881',
            'payment_mode' => 'FULL',
        ];

        // Booking pertama berhasil
        $res1 = $this->post('/booking', $postData);
        $res1->assertSessionHasNoErrors();

        // Booking kedua pada slot yang sudah habis ditolak
        $postData['customer_phone'] = '081299998882';
        $res2 = $this->post('/booking', $postData);
        $res2->assertSessionHasErrors(['slot_id']);
    }

    /**
     * c) Admin mengubah max_jeeps: endpoint wisatawan langsung memakai angka baru.
     */
    public function test_admin_changes_max_jeeps_customer_endpoint_immediately_uses_new_number(): void
    {
        $slot = ScheduleSlot::where('is_active', true)->first();
        $date = date('Y-m-d', strtotime('+7 days'));

        $slot->update(['max_jeeps' => 33]);

        $res = $this->getJson("/booking/slot-availability?trip_date={$date}&jeep_quantity=1");
        $res->assertStatus(200);

        $slots = collect($res->json('slots'));
        $target = $slots->firstWhere('id', $slot->id);

        $this->assertNotNull($target);
        $this->assertEquals(33, $target['max_jeeps']);
    }

    /**
     * d) Admin menonaktifkan sesi: sesi hilang dari endpoint wisatawan.
     */
    public function test_admin_deactivates_session_session_disappears_from_customer_endpoint(): void
    {
        $slot = ScheduleSlot::where('is_active', true)->first();
        $date = date('Y-m-d', strtotime('+7 days'));

        $slot->update(['is_active' => false]);

        $res = $this->getJson("/booking/slot-availability?trip_date={$date}&jeep_quantity=1");
        $res->assertStatus(200);

        $slots = collect($res->json('slots'));
        $target = $slots->firstWhere('id', $slot->id);

        $this->assertNull($target, 'Sesi nonaktif seharusnya tidak muncul di endpoint ketersediaan wisatawan.');
    }

    /**
     * e) Admin mengubah jadwal booking: kuota berpindah dan angka admin sama dengan wisatawan.
     */
    public function test_admin_reschedules_booking_quota_moves_and_admin_matches_customer(): void
    {
        $admin = $this->getAdminUser();

        $slots = ScheduleSlot::where('is_active', true)->take(2)->get();
        $this->assertCount(2, $slots);
        $slotA = $slots[0];
        $slotB = $slots[1];
        $date = date('Y-m-d', strtotime('+8 days'));

        $booking = Booking::create([
            'booking_code' => 'RESCHED-01',
            'customer_name' => 'Peserta Reschedule',
            'customer_phone' => '081233344455',
            'package_id' => Package::first()->id,
            'slot_id' => $slotA->id,
            'trip_date' => $date,
            'jeep_quantity' => 2,
            'passenger_count' => 8,
            'package_subtotal' => 900000,
            'total_amount' => 900000,
            'paid_amount' => 900000,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ]);

        $remA_before = SlotAvailabilityService::getRemainingJeeps($slotA->id, $date);
        $remB_before = SlotAvailabilityService::getRemainingJeeps($slotB->id, $date);

        // Admin reschedule dari slotA ke slotB
        $response = $this->actingAs($admin)->post("/admin/bookings/{$booking->id}/reschedule", [
            'trip_date' => $date,
            'slot_id' => $slotB->id,
            'reason' => 'Uji otomatis kuota berpindah',
        ]);

        $response->assertSessionHas('success');

        $remA_after = SlotAvailabilityService::getRemainingJeeps($slotA->id, $date);
        $remB_after = SlotAvailabilityService::getRemainingJeeps($slotB->id, $date);

        // Kuota slotA kembali (+2) dan kuota slotB berkurang (-2)
        $this->assertEquals($remA_before + 2, $remA_after);
        $this->assertEquals($remB_before - 2, $remB_after);

        // Pastikan endpoint admin dan endpoint wisatawan memberikan angka yang persis sama
        $adminRes = $this->actingAs($admin)->getJson("/admin/bookings/slot-availability?date={$date}");
        $customerRes = $this->getJson("/booking/slot-availability?trip_date={$date}&jeep_quantity=1");

        $adminSlots = collect($adminRes->json('slots'));
        $custSlots = collect($customerRes->json('slots'));

        $this->assertEquals(
            $adminSlots->firstWhere('id', $slotB->id)['remaining_jeeps'],
            $custSlots->firstWhere('id', $slotB->id)['remaining']
        );
    }
}
