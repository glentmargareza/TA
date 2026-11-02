<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon_type',
        'link',
        'is_read',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk aktivitas terbaru
     * 
     * @param Builder $query
     * @param int $limit
     * @return Builder
     */
    public function scopeRecent(Builder $query, int $limit = 20): Builder
    {
        return $query->orderBy('id', 'desc')->limit($limit);
    }

    /**
     * Catat notifikasi: Ada booking baru
     */
    public static function logBookingNew(Booking $booking): self
    {
        $pkgName = $booking->package?->name ?? 'Paket Trip';
        $tripDate = $booking->trip_date ? $booking->trip_date->format('d M Y') : '-';

        return self::create([
            'user_id' => $booking->user_id ?? Auth::id(),
            'type' => 'BOOKING_NEW',
            'title' => 'Pesanan Baru Masuk! 🎉',
            'message' => "Wisatawan {$booking->customer_name} baru saja memesan {$pkgName} untuk trip tanggal {$tripDate}. Rincian: {$booking->jeep_quantity} unit Jeep ({$booking->passenger_count} orang penumpang). Kode: #{$booking->booking_code}.",
            'icon_type' => 'booking',
            'link' => route('admin.bookings', ['search' => $booking->booking_code]),
            'is_read' => false,
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->customer_name,
                'package_name' => $pkgName,
                'jeep_quantity' => $booking->jeep_quantity,
            ],
        ]);
    }

    /**
     * Catat notifikasi: Ada pembayaran berhasil
     */
    public static function logPaymentSuccess(Booking $booking, float $amount, string $channel = 'Midtrans Gateway'): self
    {
        $formattedAmount = 'Rp ' . number_format($amount, 0, ',', '.');
        $statusLabel = $booking->booking_status === 'FULL_PAID' ? 'LUNAS (100%)' : 'Uang Muka DP (30%)';

        return self::create([
            'user_id' => Auth::id(),
            'type' => 'PAYMENT_SUCCESS',
            'title' => 'Pembayaran Berhasil Diterima 💰',
            'message' => "Uang pembayaran {$statusLabel} sebesar {$formattedAmount} berhasil diterima melalui {$channel} untuk booking #{$booking->booking_code} atas nama {$booking->customer_name}.",
            'icon_type' => 'payment',
            'link' => route('admin.bookings', ['search' => $booking->booking_code]),
            'is_read' => false,
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'amount' => $amount,
                'channel' => $channel,
                'booking_status' => $booking->booking_status,
            ],
        ]);
    }

    /**
     * Catat notifikasi: Ada pembatalan booking
     */
    public static function logBookingCancelled(Booking $booking, string $actor = 'Admin Loket'): self
    {
        return self::create([
            'user_id' => Auth::id(),
            'type' => 'BOOKING_CANCELLED',
            'title' => 'Pesanan Dibatalkan ❌',
            'message' => "Pemesanan #{$booking->booking_code} atas nama {$booking->customer_name} telah dibatalkan ({$actor}). Kuota armada Jeep terkait telah otomatis dibebaskan kembali.",
            'icon_type' => 'cancel',
            'link' => route('admin.bookings', ['search' => $booking->booking_code]),
            'is_read' => false,
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'customer_name' => $booking->customer_name,
                'actor' => $actor,
            ],
        ]);
    }

    /**
     * Catat notifikasi: Jadwal sudah penuh
     */
    public static function logScheduleFull(ScheduleSlot|string $slot, string $tripDate, int $totalJeeps = 20): self
    {
        $slotLabel = is_object($slot) ? $slot->slot_label : $slot;

        return self::create([
            'user_id' => Auth::id(),
            'type' => 'SCHEDULE_FULL',
            'title' => 'Peringatan: Kuota Jadwal Penuh! ⚠️',
            'message' => "Kapasitas armada untuk sesi {$slotLabel} pada tanggal {$tripDate} telah mencapai kuota maksimal ({$totalJeeps}/{$totalJeeps} Jeep terisi). Pemesanan baru pada jam tersebut otomatis ditutup sementara.",
            'icon_type' => 'schedule',
            'link' => route('admin.schedules'),
            'is_read' => false,
            'metadata' => [
                'slot' => $slotLabel,
                'trip_date' => $tripDate,
                'total_jeeps' => $totalJeeps,
            ],
        ]);
    }

    /**
     * Catat riwayat aktivitas admin
     */
    public static function logAdmin(string $title, string $message, string $iconType = 'admin', ?string $link = null, ?array $metadata = null): self
    {
        $admin = Auth::user();
        $adminName = $admin ? $admin->name : 'Admin Loket';

        return self::create([
            'user_id' => $admin?->id,
            'type' => 'ADMIN_ACTION',
            'title' => "{$title} 👤",
            'message' => "{$adminName}: {$message}",
            'icon_type' => $iconType,
            'link' => $link,
            'is_read' => false,
            'metadata' => $metadata,
        ]);
    }
}

