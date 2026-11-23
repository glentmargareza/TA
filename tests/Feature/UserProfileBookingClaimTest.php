<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserProfileBookingClaimTest extends TestCase
{
    use DatabaseTransactions;

    protected function createTestBooking(array $attributes = []): Booking
    {
        $package = \Illuminate\Support\Facades\DB::table('packages')->first();
        $slot = \Illuminate\Support\Facades\DB::table('schedule_slots')->first();

        return Booking::create(array_merge([
            'booking_code' => 'WKR-' . strtoupper(Str::random(8)),
            'customer_name' => 'Wisatawan Uji',
            'customer_phone' => '081234567890',
            'customer_email' => 'wisatawan@example.com',
            'package_id' => $package->id,
            'slot_id' => $slot->id,
            'trip_date' => now()->addDays(2)->format('Y-m-d'),
            'jeep_quantity' => 1,
            'passenger_count' => 4,
            'arrival_type' => 'self_drive',
            'package_subtotal' => 450000,
            'pickup_fee' => 0,
            'total_amount' => 450000,
            'paid_amount' => 450000,
            'remaining_amount' => 0,
            'payment_mode' => 'FULL',
            'booking_status' => 'FULL_PAID',
        ], $attributes));
    }

    public function test_profile_page_has_no_svg_or_star_icons()
    {
        $user = User::create([
            'name' => 'Budi Tester',
            'email' => 'budi_tester@example.com',
            'phone' => '081234567890',
            'role' => 'user',
            'password' => Hash::make('password123'),
        ]);

        $booking = $this->createTestBooking([
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
        ]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Tiket', false);
        $response->assertSee('Klaim Booking dengan Kode', false);
        $response->assertSee($booking->booking_code);

        // Pastikan view auth.profile tidak memiliki svg, ★, atau ☆
        $profileView = file_get_contents(resource_path('views/auth/profile.blade.php'));
        $this->assertStringNotContainsString('<svg', $profileView);
        $this->assertStringNotContainsString('★', $profileView);
        $this->assertStringNotContainsString('☆', $profileView);
    }

    public function test_guest_booking_automatically_claimed_on_login()
    {
        $user = User::create([
            'name' => 'Rian Login',
            'email' => 'rian_login@example.com',
            'phone' => '081298765432',
            'role' => 'user',
            'password' => Hash::make('secret123'),
        ]);

        // Booking dibuat guest tanpa email, hanya nomor telepon yang sama
        $booking = $this->createTestBooking([
            'customer_name' => 'Rian Guest',
            'customer_phone' => '081298765432',
            'customer_email' => null,
        ]);

        $response = $this->withSession(['pending_booking_code' => $booking->booking_code])
            ->post(route('login.post'), [
                'email' => 'rian_login@example.com',
                'password' => 'secret123',
            ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);

        // Verifikasi email booking di-update ke email user
        $booking->refresh();
        $this->assertEquals('rian_login@example.com', $booking->customer_email);

        // Verifikasi muncul di halaman profil
        $profileRes = $this->actingAs($user)->get(route('profile'));
        $profileRes->assertSee($booking->booking_code);
    }

    public function test_guest_booking_automatically_claimed_on_register()
    {
        $booking = $this->createTestBooking([
            'customer_name' => 'Citra Guest',
            'customer_phone' => '087711223344',
            'customer_email' => null,
        ]);

        $response = $this->withSession(['pending_booking_code' => $booking->booking_code])
            ->post(route('register.post'), [
                'name' => 'Citra Registrasi',
                'email' => 'citra_reg@example.com',
                'phone' => '087711223344',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect();
        $this->assertAuthenticated();

        $booking->refresh();
        $this->assertEquals('citra_reg@example.com', $booking->customer_email);

        $user = User::where('email', 'citra_reg@example.com')->first();
        $profileRes = $this->actingAs($user)->get(route('profile'));
        $profileRes->assertSee($booking->booking_code);
    }

    public function test_manual_claim_booking_succeeds_with_matching_phone()
    {
        $user = User::create([
            'name' => 'Doni Klaim',
            'email' => 'doni@example.com',
            'phone' => '085611223344',
            'role' => 'user',
            'password' => Hash::make('password123'),
        ]);

        // Booking format phone internasional +62
        $booking = $this->createTestBooking([
            'customer_name' => 'Doni',
            'customer_phone' => '+6285611223344',
            'customer_email' => null,
        ]);

        $response = $this->actingAs($user)->post(route('booking.claim'), [
            'booking_code' => $booking->booking_code,
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('doni@example.com', $booking->customer_email);
    }

    public function test_manual_claim_rejected_if_phone_and_email_mismatch()
    {
        $user = User::create([
            'name' => 'Hacker Claim',
            'email' => 'hacker@example.com',
            'phone' => '089999999999',
            'role' => 'user',
            'password' => Hash::make('password123'),
        ]);

        $victimBooking = $this->createTestBooking([
            'customer_name' => 'Victim',
            'customer_phone' => '081234567890',
            'customer_email' => 'victim@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('booking.claim'), [
            'booking_code' => $victimBooking->booking_code,
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('error');

        // Pastikan email booking korban TIDAK berubah
        $victimBooking->refresh();
        $this->assertEquals('victim@example.com', $victimBooking->customer_email);
    }

    public function test_manual_claim_non_existent_booking_fails()
    {
        $user = User::create([
            'name' => 'User Valid',
            'email' => 'uservalid@example.com',
            'phone' => '081122334455',
            'role' => 'user',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->post(route('booking.claim'), [
            'booking_code' => 'WKR-FAKE999',
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('error');
    }
}
