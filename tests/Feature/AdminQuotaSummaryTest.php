<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminQuotaSummaryTest extends TestCase
{
    use DatabaseTransactions;

    protected function getAdminUser(): User
    {
        return User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
    }

    /**
     * a) Tanggal tanpa booking: Total Armada 20, Dipesan 0 (tanpa keterangan sesi), Tersedia 20.
     * Tidak ada ikon di ketiga kartu.
     */
    public function test_date_without_booking_shows_capacity_and_zero_booked(): void
    {
        $admin = $this->getAdminUser();
        $date = '2026-12-01'; // Tanggal bersih tanpa booking

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        // Judul kartu pertama harus "Total Armada" bukan "Total Kapasitas"
        $response->assertSee('Total Armada');
        $response->assertDontSee('Total Kapasitas');

        // Angka 20 Armada, 0 Dipesan, 20 Tersedia
        $response->assertSee('20');
        $response->assertSee('0');
        $response->assertDontSee('sesi terpadat:');

        // Pastikan tidak ada ikon box di kartu ringkasan
        $response->assertDontSee('bg-slate-100 text-slate-600 flex items-center justify-center', false);
        $response->assertDontSee('bg-amber-100 text-amber-700 flex items-center justify-center', false);
    }

    /**
     * b) Satu booking 1 jeep di sesi 10:30: Dipesan 1 "sesi terpadat: Sesi Pagi 2 (10:30 WIB)", Tersedia 19.
     */
    public function test_single_booking_at_1030_shows_peak_session_and_remaining(): void
    {
        $admin = $this->getAdminUser();
        $slot1030 = ScheduleSlot::where('start_time', 'like', '10:30%')->firstOrFail();
        $date = '2026-12-02';

        Booking::create([
            'booking_code' => 'TEST-B1',
            'customer_name' => 'Budi Single',
            'customer_phone' => '081234567891',
            'package_id' => Package::first()->id,
            'slot_id' => $slot1030->id,
            'trip_date' => $date,
            'jeep_quantity' => 1,
            'passenger_count' => 4,
            'package_subtotal' => 450000,
            'total_amount' => 450000,
            'paid_amount' => 450000,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ]);

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        $response->assertSee('Total Armada');
        $response->assertSee('sesi terpadat: ' . $slot1030->slot_label);
    }

    /**
     * c) Sesi A berisi 3 jeep dan Sesi B berisi 5 jeep: Dipesan 5, Tersedia 15.
     */
    public function test_multiple_sessions_uses_peak_session_count(): void
    {
        $admin = $this->getAdminUser();
        $slots = ScheduleSlot::where('is_active', true)->where('max_jeeps', '>=', 10)->take(2)->get();
        $slotA = $slots[0];
        $slotB = $slots[1];
        $date = '2026-12-03';

        // Sesi A: 3 jeep
        Booking::create([
            'booking_code' => 'TEST-C1',
            'customer_name' => 'Peserta A',
            'customer_phone' => '081234567892',
            'package_id' => Package::first()->id,
            'slot_id' => $slotA->id,
            'trip_date' => $date,
            'jeep_quantity' => 3,
            'passenger_count' => 12,
            'package_subtotal' => 1350000,
            'total_amount' => 1350000,
            'paid_amount' => 1350000,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ]);

        // Sesi B: 5 jeep
        Booking::create([
            'booking_code' => 'TEST-C2',
            'customer_name' => 'Peserta B',
            'customer_phone' => '081234567893',
            'package_id' => Package::first()->id,
            'slot_id' => $slotB->id,
            'trip_date' => $date,
            'jeep_quantity' => 5,
            'passenger_count' => 20,
            'package_subtotal' => 2250000,
            'total_amount' => 2250000,
            'paid_amount' => 2250000,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ]);

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        // View harus menerima quotaSummary dengan total_booked = 5, total_remaining = 15, peak_label = slotB
        $quotaSummary = $response->viewData('quotaSummary');
        $this->assertEquals(20, $quotaSummary['total_capacity']);
        $this->assertEquals(5, $quotaSummary['total_booked']);
        $this->assertEquals(15, $quotaSummary['total_remaining']);
        $this->assertEquals($slotB->slot_label, $quotaSummary['peak_label']);
    }

    /**
     * d) Booking dibatalkan atau PENDING kedaluwarsa: angka kembali normal.
     */
    public function test_cancelled_or_expired_bookings_do_not_count(): void
    {
        $admin = $this->getAdminUser();
        $slot = ScheduleSlot::where('is_active', true)->firstOrFail();
        $date = '2026-12-04';

        // 1. CANCELLED booking
        Booking::create([
            'booking_code' => 'TEST-D1',
            'customer_name' => 'Peserta Batal',
            'customer_phone' => '081234567894',
            'package_id' => Package::first()->id,
            'slot_id' => $slot->id,
            'trip_date' => $date,
            'jeep_quantity' => 4,
            'passenger_count' => 16,
            'package_subtotal' => 1800000,
            'total_amount' => 1800000,
            'paid_amount' => 0,
            'payment_mode' => 'FULL',
            'booking_status' => 'CANCELLED',
        ]);

        // 2. Expired PENDING booking
        Booking::create([
            'booking_code' => 'TEST-D2',
            'customer_name' => 'Peserta Expired',
            'customer_phone' => '081234567895',
            'package_id' => Package::first()->id,
            'slot_id' => $slot->id,
            'trip_date' => $date,
            'jeep_quantity' => 3,
            'passenger_count' => 12,
            'package_subtotal' => 1350000,
            'total_amount' => 1350000,
            'paid_amount' => 0,
            'payment_mode' => 'FULL',
            'booking_status' => 'PENDING',
            'lock_expires_at' => now()->subMinutes(15),
        ]);

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        $quotaSummary = $response->viewData('quotaSummary');
        $this->assertEquals(20, $quotaSummary['total_capacity']);
        $this->assertEquals(0, $quotaSummary['total_booked']);
        $this->assertEquals(20, $quotaSummary['total_remaining']);
        $this->assertNull($quotaSummary['peak_label']);
    }

    /**
     * e) Sesi dinonaktifkan: tidak ikut dihitung.
     */
    public function test_deactivated_session_not_included_in_quota(): void
    {
        $admin = $this->getAdminUser();
        $slot = ScheduleSlot::where('is_active', true)->firstOrFail();
        $date = '2026-12-05';

        // Buat booking pada slot ini
        Booking::create([
            'booking_code' => 'TEST-E1',
            'customer_name' => 'Peserta Sesi Nonaktif',
            'customer_phone' => '081234567896',
            'package_id' => Package::first()->id,
            'slot_id' => $slot->id,
            'trip_date' => $date,
            'jeep_quantity' => 2,
            'passenger_count' => 8,
            'package_subtotal' => 900000,
            'total_amount' => 900000,
            'paid_amount' => 900000,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ]);

        // Nonaktifkan slot
        $slot->update(['is_active' => false]);

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        $quotaSummary = $response->viewData('quotaSummary');
        // Sesi yang dinonaktifkan tidak dihitung, jadi total_booked = 0
        $this->assertEquals(0, $quotaSummary['total_booked']);
        $this->assertNull($quotaSummary['peak_label']);
    }

    /**
     * f) Tanggal 24/11/2026 di halaman admin: tampil 20, 1, 19 dan tidak ada ikon di ketiga kartu.
     */
    public function test_target_date_24_nov_2026_values_and_no_icons(): void
    {
        $admin = $this->getAdminUser();
        $date = '2026-11-24';

        $response = $this->actingAs($admin)->get("/admin/bookings?trip_date={$date}");
        $response->assertStatus(200);

        $quotaSummary = $response->viewData('quotaSummary');
        $this->assertEquals(20, $quotaSummary['total_capacity']);
        $this->assertEquals(1, $quotaSummary['total_booked']);
        $this->assertEquals(19, $quotaSummary['total_remaining']);
        $this->assertEquals('Sesi Pagi 2 (10:30 WIB)', $quotaSummary['peak_label']);

        // Pastikan tampilan teks dan tidak ada emoji ikon di kartu
        $response->assertSee('Total Armada');
        $response->assertDontSee('Total Kapasitas');
        $response->assertSee('sesi terpadat: Sesi Pagi 2 (10:30 WIB)');
        $response->assertDontSee('bg-slate-100 text-slate-600 flex items-center justify-center', false);
        $response->assertDontSee('bg-amber-100 text-amber-700 flex items-center justify-center', false);
    }

    /**
     * Validasi Controller Jadwal: max_jeeps tidak boleh melebihi total_capacity.
     */
    public function test_schedule_controller_rejects_max_jeeps_exceeding_total_capacity(): void
    {
        $admin = $this->getAdminUser();
        $slot = ScheduleSlot::firstOrFail();

        // Coba buat sesi baru dengan max_jeeps = 25 (melebihi kapasitas armada 20)
        $resStore = $this->actingAs($admin)->post(route('admin.schedules.store'), [
            'slot_label' => 'Sesi Ekstra',
            'start_time' => '16:30',
            'end_time' => '18:30',
            'max_jeeps' => 25,
        ]);
        $resStore->assertSessionHasErrors(['max_jeeps']);

        // Coba update sesi dengan max_jeeps = 30
        $resUpdate = $this->actingAs($admin)->put(route('admin.schedules.update', $slot->id), [
            'slot_label' => $slot->slot_label,
            'start_time' => '08:00',
            'end_time' => '10:30',
            'max_jeeps' => 30,
        ]);
        $resUpdate->assertSessionHasErrors(['max_jeeps']);

        // Update dengan max_jeeps = 20 (sama dengan armada) harus sukses
        $resValid = $this->actingAs($admin)->put(route('admin.schedules.update', $slot->id), [
            'slot_label' => $slot->slot_label,
            'start_time' => '08:00',
            'end_time' => '10:30',
            'max_jeeps' => 20,
        ]);
        $resValid->assertSessionHasNoErrors();
    }
}
