<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Jeep;
use App\Models\Driver;
use App\Models\BookingAssignment;
use App\Models\User;
use App\Models\Package;
use App\Models\ScheduleSlot;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AdminController extends Controller
{
    public function dashboard()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $totalRevenue = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount');
        $readyJeepsCount = Jeep::where('status', 'READY')->count();
        $totalJeepsCount = Jeep::count();
        $pendingDpCount = Booking::where('booking_status', 'DP_PAID')->count();
        $pendingDpAmount = Booking::where('booking_status', 'DP_PAID')->sum('remaining_amount');
        $waitingVerificationCount = Booking::where('booking_status', 'WAITING_CONFIRMATION')->count();

        $bookings = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver', 'payments'])
            ->orderBy('id', 'desc')
            ->get();

        $jeeps = Jeep::all();
        $drivers = Driver::where('is_active', true)->get();
        $totalReviewsCount = Review::count();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'readyJeepsCount',
            'totalJeepsCount',
            'pendingDpCount',
            'pendingDpAmount',
            'waitingVerificationCount',
            'bookings',
            'jeeps',
            'drivers',
            'totalReviewsCount'
        ));
    }

    /**
     * LAPORAN PENJUALAN
     */
    public function reports(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin.');
        }

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status', 'ALL');
        $packageId = $request->query('package_id');

        $query = Booking::with(['package', 'slot']);

        if (!empty($startDate)) {
            $query->whereDate('trip_date', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('trip_date', '<=', $endDate);
        }
        if ($status && $status !== 'ALL') {
            $query->where('booking_status', $status);
        }
        if (!empty($packageId)) {
            $query->where('package_id', $packageId);
        }

        // Hitung KPIs
        $totalRevenue = (clone $query)->whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount');
        $totalBilled = (clone $query)->sum('total_amount');
        $totalUnpaid = (clone $query)->where('booking_status', 'DP_PAID')->sum('remaining_amount');
        $totalOrders = (clone $query)->count();
        $totalJeepsBooked = (clone $query)->sum('jeep_quantity');
        $avgOrderValue = $totalOrders > 0 ? ($totalBilled / $totalOrders) : 0;

        $bookings = $query->orderBy('trip_date', 'desc')->paginate(15)->withQueryString();
        $packages = Package::all();

        return view('admin.reports', compact(
            'bookings',
            'packages',
            'totalRevenue',
            'totalBilled',
            'totalUnpaid',
            'totalOrders',
            'totalJeepsBooked',
            'avgOrderValue',
            'startDate',
            'endDate',
            'status',
            'packageId'
        ));
    }

    /**
     * EKSPOR LAPORAN PENJUALAN KE CSV
     */
    public function exportReportsCsv(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status', 'ALL');
        $packageId = $request->query('package_id');

        $query = Booking::with(['package', 'slot']);

        if (!empty($startDate)) $query->whereDate('trip_date', '>=', $startDate);
        if (!empty($endDate)) $query->whereDate('trip_date', '<=', $endDate);
        if ($status && $status !== 'ALL') $query->where('booking_status', $status);
        if (!empty($packageId)) $query->where('package_id', $packageId);

        $bookings = $query->orderBy('trip_date', 'desc')->get();

        $filename = 'Laporan_Penjualan_Jeep_Wukirsari_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            // Menulis BOM agar Excel membuka dengan UTF-8
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'No',
                'Kode Booking',
                'Tanggal Trip',
                'Nama Pemesan',
                'No WhatsApp',
                'Email',
                'Paket Wisata',
                'Sesi Jam',
                'Jumlah Jeep',
                'Status Pembayaran',
                'Total Tagihan (Rp)',
                'Sudah Dibayar (Rp)',
                'Sisa Pelunasan (Rp)',
            ]);

            foreach ($bookings as $idx => $b) {
                fputcsv($handle, [
                    $idx + 1,
                    $b->booking_code,
                    $b->trip_date ? $b->trip_date->format('d/m/Y') : '-',
                    $b->customer_name,
                    $b->customer_phone,
                    $b->customer_email,
                    $b->package->name ?? '-',
                    $b->slot->slot_label ?? '-',
                    $b->jeep_quantity,
                    $b->booking_status,
                    $b->total_amount,
                    $b->paid_amount,
                    $b->remaining_amount,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * KELOLA PAKET WISATA
     */
    public function packages()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin.');
        }

        $packages = Package::orderBy('id', 'asc')->get();
        return view('admin.packages', compact('packages'));
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:packages,slug',
            'price_per_jeep' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10',
            'duration_text' => 'nullable|string|max:100',
            'category_badge' => 'nullable|string|max:50',
            'cover_image' => 'nullable|url|max:500',
            'description' => 'required|string',
            'routes_raw' => 'nullable|string',
        ]);

        $routesList = [];
        if (!empty($validated['routes_raw'])) {
            $routesList = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['routes_raw']))));
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'price_per_jeep' => $validated['price_per_jeep'],
            'duration_minutes' => $validated['duration_minutes'],
            'duration_text' => $validated['duration_text'] ?? ($validated['duration_minutes'] . ' Menit'),
            'destinations_count' => count($routesList),
            'routes_list' => $routesList,
            'category_badge' => $validated['category_badge'] ?? 'Paket Pilihan',
            'cover_image' => $validated['cover_image'] ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1000&q=80',
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Paket wisata {$validated['name']} berhasil ditambahkan!");
    }

    public function updatePackage(Request $request, int $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:packages,slug,' . $id,
            'price_per_jeep' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:10',
            'duration_text' => 'nullable|string|max:100',
            'category_badge' => 'nullable|string|max:50',
            'cover_image' => 'nullable|url|max:500',
            'description' => 'required|string',
            'routes_raw' => 'nullable|string',
        ]);

        $routesList = [];
        if (!empty($validated['routes_raw'])) {
            $routesList = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['routes_raw']))));
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        $package->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'price_per_jeep' => $validated['price_per_jeep'],
            'duration_minutes' => $validated['duration_minutes'],
            'duration_text' => $validated['duration_text'] ?? $package->duration_text,
            'destinations_count' => count($routesList) > 0 ? count($routesList) : $package->destinations_count,
            'routes_list' => count($routesList) > 0 ? $routesList : $package->routes_list,
            'category_badge' => $validated['category_badge'] ?? $package->category_badge,
            'cover_image' => $validated['cover_image'] ?? $package->cover_image,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', "Paket wisata {$package->name} berhasil diperbarui!");
    }

    public function togglePackage(int $id)
    {
        $package = Package::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        $status = $package->is_active ? 'Diaktifkan' : 'Dinonaktifkan';
        return redirect()->back()->with('success', "Status paket {$package->name} berhasil {$status}!");
    }

    public function destroyPackage(int $id)
    {
        $package = Package::findOrFail($id);
        $name = $package->name;
        $package->delete();

        return redirect()->back()->with('success', "Paket wisata {$name} berhasil dihapus!");
    }

    /**
     * KELOLA JADWAL & SESI
     */
    public function schedules()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin.');
        }

        $slots = ScheduleSlot::withCount('bookings')->orderBy('start_time', 'asc')->get();
        return view('admin.schedules', compact('slots'));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'slot_label' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_jeeps' => 'required|integer|min:1|max:50',
        ]);

        ScheduleSlot::create([
            'slot_label' => $validated['slot_label'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_jeeps' => $validated['max_jeeps'],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Sesi jadwal {$validated['slot_label']} berhasil ditambahkan!");
    }

    public function updateSchedule(Request $request, int $id)
    {
        $slot = ScheduleSlot::findOrFail($id);

        $validated = $request->validate([
            'slot_label' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_jeeps' => 'required|integer|min:1|max:50',
        ]);

        $slot->update([
            'slot_label' => $validated['slot_label'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_jeeps' => $validated['max_jeeps'],
        ]);

        return redirect()->back()->with('success', "Jadwal sesi {$slot->slot_label} berhasil diperbarui!");
    }

    public function toggleSchedule(int $id)
    {
        $slot = ScheduleSlot::findOrFail($id);
        $slot->is_active = !$slot->is_active;
        $slot->save();

        $status = $slot->is_active ? 'Diaktifkan (Siaga)' : 'Dinonaktifkan (Libur)';
        return redirect()->back()->with('success', "Status sesi {$slot->slot_label} berhasil {$status}!");
    }

    public function destroySchedule(int $id)
    {
        $slot = ScheduleSlot::findOrFail($id);
        $label = $slot->slot_label;
        $slot->delete();

        return redirect()->back()->with('success', "Sesi jadwal {$label} berhasil dihapus!");
    }

    /**
     * MANAJEMEN ULASAN
     */
    public function reviews(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $rating = $request->query('rating');
        $search = $request->query('search');

        $query = Review::with(['user', 'package']);

        if ($rating) {
            $query->where('rating', (int)$rating);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();
        $totalReviews = Review::count();
        $avgRating = Review::avg('rating') ?: 5.0;
        $fiveStarCount = Review::where('rating', 5)->count();

        return view('admin.reviews', compact(
            'reviews',
            'totalReviews',
            'avgRating',
            'fiveStarCount',
            'rating',
            'search'
        ));
    }

    public function settleDp(int $id)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban.');
        }

        return DB::transaction(function () use ($id, $user) {
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            // Guard: Hanya booking DP_PAID yang memiliki sisa tagihan valid yang boleh dilunasi
            if ($booking->booking_status !== 'DP_PAID' || (float) $booking->remaining_amount <= 0) {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} tidak memiliki sisa tagihan untuk dilunasi.");
            }

            // Tangkap nilai sisa tagihan sebelum di-nol-kan
            $settlementAmount = (int) round($booking->remaining_amount);

            $adminSettlementMeta = [
                'settled_by_admin_id' => $user->id,
                'settled_by_admin_name' => $user->full_name ?? $user->name,
                'settled_at' => now()->toDateTimeString(),
                'method' => 'Pelunasan Tunai di Loket Basecamp',
            ];

            // Catat transaksi pelunasan tunai di kasir basecamp
            Payment::create([
                'booking_id' => $booking->id,
                'order_id' => 'SETTLE-' . $booking->booking_code . '-' . strtoupper(Str::random(6)),
                'transaction_ref' => 'LOKET-' . $booking->booking_code . '-' . strtoupper(Str::random(6)),
                'payment_stage' => 'SETTLEMENT',
                'payment_type' => 'CASH_BASECAMP',
                'payment_method' => 'cash_counter',
                'gross_amount' => $settlementAmount,
                'payment_status' => 'SETTLED',
                'paid_at' => now(),
                'payload_response' => $adminSettlementMeta,
            ]);

            // Perbarui status booking menjadi lunas
            $booking->update([
                'booking_status' => 'FULL_PAID',
                'paid_amount' => $booking->total_amount,
                'remaining_amount' => 0.00,
                'lock_expires_at' => null,
            ]);

            return redirect()->back()->with('success', "Pelunasan tunai sebesar Rp " . number_format($settlementAmount, 0, ',', '.') . " untuk booking {$booking->booking_code} berhasil dicatat!");
        });
    }

    public function assignDriver(Request $request, int $id)
    {
        $validated = $request->validate([
            'jeep_id' => 'required|exists:jeeps,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        BookingAssignment::updateOrCreate(
            ['booking_id' => $id],
            [
                'jeep_id' => $validated['jeep_id'],
                'driver_id' => $validated['driver_id'],
                'assigned_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Armada dan driver berhasil ditugaskan!');
    }

    /**
     * Menyetujui bukti transfer manual Bank BPD DIY
     */
    public function verifyPayment(Request $request, int $id)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban.');
        }

        return DB::transaction(function () use ($id, $user) {
            /** @var Booking $booking */
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            // Guard status: hanya proses jika masih menunggu konfirmasi
            if ($booking->booking_status !== 'WAITING_CONFIRMATION') {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} sudah diproses atau statusnya telah berubah.");
            }

            // Temukan transaksi pembayaran pending terkait transfer manual
            $payment = Payment::where('booking_id', $booking->id)
                ->where('payment_type', 'MANUAL_BPD_DIY')
                ->where('payment_status', 'PENDING')
                ->latest()
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                return redirect()->back()->with('error', "Tidak ditemukan data bukti transfer pending untuk booking {$booking->booking_code}.");
            }

            $totalAmountInt = (int) round($booking->total_amount);
            $grossInt = (int) round($payment->gross_amount);

            // Tentukan status akhir: FULL_PAID atau DP_PAID
            if ($payment->payment_stage === 'FULL_PAYMENT' || $booking->payment_mode === 'FULL' || $grossInt >= $totalAmountInt) {
                $newBookingStatus = 'FULL_PAID';
                $newPaidAmount = $totalAmountInt;
                $newRemainingAmount = 0.00;
            } else {
                $newBookingStatus = 'DP_PAID';
                $newPaidAmount = $grossInt;
                $newRemainingAmount = max(0.00, $totalAmountInt - $grossInt);
            }

            $adminVerificationMeta = [
                'verified_by_admin_id' => $user->id,
                'verified_by_admin_name' => $user->full_name ?? $user->name,
                'verified_at' => now()->toDateTimeString(),
                'method' => 'Bank BPD DIY Manual Transfer Verification',
            ];

            // 1. Update Payment menjadi SETTLED sah dengan audit log admin
            $payment->update([
                'payment_status' => 'SETTLED',
                'paid_at' => now(),
                'payload_response' => $adminVerificationMeta,
            ]);

            // Batalkan pembayaran pending metode lain jika ada
            Payment::where('booking_id', $booking->id)
                ->where('id', '!=', $payment->id)
                ->where('payment_status', 'PENDING')
                ->update(['payment_status' => 'EXPIRED']);

            // 2. Update status Booking dengan nominal sah dan bebaskan timer lock
            $booking->update([
                'booking_status' => $newBookingStatus,
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'lock_expires_at' => null,
            ]);

            return redirect()->back()->with('success', "Pembayaran transfer Bank BPD DIY sebesar Rp " . number_format($grossInt, 0, ',', '.') . " untuk booking {$booking->booking_code} berhasil disetujui ({$newBookingStatus})!");
        });
    }

    /**
     * Mengonfirmasi penerimaan pembayaran uang tunai (Cash) di kasir loket basecamp
     */
    public function confirmCashPayment(Request $request, int $id)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban.');
        }

        return DB::transaction(function () use ($id, $user) {
            /** @var Booking $booking */
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            // 1. Guard: Cegah konfirmasi ganda
            if (in_array($booking->booking_status, ['FULL_PAID', 'DP_PAID'])) {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} sudah berstatus terbayar sah sebelumnya.");
            }

            // 2. Guard: Cegah booking yang sudah dibatalkan
            if ($booking->booking_status === 'CANCELLED') {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} telah dibatalkan dan tidak dapat dikonfirmasi.");
            }

            // Temukan transaksi pembayaran pending terkait CASH
            $payment = Payment::where('booking_id', $booking->id)
                ->where('payment_type', 'CASH')
                ->where('payment_status', 'PENDING')
                ->latest()
                ->lockForUpdate()
                ->first();

            $totalAmountInt = (int) round($booking->total_amount);
            $payableAmount = (int) round($booking->paid_amount);
            $grossInt = $payment ? (int) round($payment->gross_amount) : $payableAmount;

            // Tentukan status akhir: FULL_PAID atau DP_PAID
            if (($payment && $payment->payment_stage === 'FULL_PAYMENT') || $booking->payment_mode === 'FULL' || $grossInt >= $totalAmountInt) {
                $newBookingStatus = 'FULL_PAID';
                $newPaidAmount = $totalAmountInt;
                $newRemainingAmount = 0.00;
                $stage = 'FULL_PAYMENT';
            } else {
                $newBookingStatus = 'DP_PAID';
                $newPaidAmount = $grossInt;
                $newRemainingAmount = max(0.00, $totalAmountInt - $grossInt);
                $stage = 'DOWN_PAYMENT';
            }

            $adminConfirmationMeta = [
                'confirmed_by_admin_id' => $user->id,
                'confirmed_by_admin_name' => $user->full_name ?? $user->name,
                'confirmed_at' => now()->toDateTimeString(),
                'location' => 'Loket Kasir Basecamp Jeep Wukirsari',
            ];

            if ($payment) {
                $payment->update([
                    'payment_status' => 'SETTLED',
                    'paid_at' => now(),
                    'payload_response' => $adminConfirmationMeta,
                ]);
            } else {
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'order_id' => 'CASH-' . $booking->booking_code . '-' . strtoupper(Str::random(6)),
                    'transaction_ref' => 'LOKET-' . strtoupper(Str::random(8)),
                    'payment_stage' => $stage,
                    'payment_type' => 'CASH',
                    'payment_method' => 'cash_counter',
                    'gross_amount' => $grossInt,
                    'payment_status' => 'SETTLED',
                    'paid_at' => now(),
                    'payload_response' => $adminConfirmationMeta,
                ]);
            }

            // Batalkan pembayaran pending metode lain jika ada
            Payment::where('booking_id', $booking->id)
                ->where('id', '!=', $payment->id)
                ->where('payment_status', 'PENDING')
                ->update(['payment_status' => 'EXPIRED']);

            // Perbarui status booking
            $booking->update([
                'booking_status' => $newBookingStatus,
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'lock_expires_at' => null,
            ]);

            return redirect()->back()->with('success', "Pembayaran tunai sebesar Rp " . number_format($grossInt, 0, ',', '.') . " untuk booking {$booking->booking_code} berhasil dikonfirmasi ({$newBookingStatus}) oleh {$user->name}. E-Tiket resmi telah aktif!");
        });
    }

    /**
     * Menolak bukti transfer manual Bank BPD DIY
     */
    public function rejectPayment(Request $request, int $id)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan bukti transfer wajib diisi.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        return DB::transaction(function () use ($id, $validated) {
            /** @var Booking $booking */
            $booking = Booking::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($booking->booking_status !== 'WAITING_CONFIRMATION') {
                return redirect()->back()->with('error', "Pesanan {$booking->booking_code} sudah diproses atau statusnya telah berubah.");
            }

            $payment = Payment::where('booking_id', $booking->id)
                ->where('payment_type', 'MANUAL_BPD_DIY')
                ->where('payment_status', 'PENDING')
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($payment) {
                $payment->update([
                    'payment_status' => 'FAILED',
                    'rejection_reason' => $validated['rejection_reason'],
                ]);
            }

            // Hitung toleransi waktu perbaikan (maksimal 2 jam atau sampai sesi dimulai)
            $slotStartTime = $booking->slot ? $booking->slot->start_time : '08:00:00';
            $tripDateStr = $booking->trip_date instanceof \DateTimeInterface 
                ? $booking->trip_date->format('Y-m-d') 
                : \Illuminate\Support\Carbon::parse($booking->trip_date)->format('Y-m-d');
            $sessionDateTime = \Illuminate\Support\Carbon::parse($tripDateStr . ' ' . $slotStartTime);
            $reuploadExpiry = now()->addHours(2);
            if ($reuploadExpiry->greaterThan($sessionDateTime)) {
                $reuploadExpiry = $sessionDateTime;
            }

            // Kembalikan status booking menjadi PENDING agar customer dapat mengunggah bukti perbaikan
            $booking->update([
                'booking_status' => 'PENDING',
                'lock_expires_at' => $reuploadExpiry,
            ]);

            return redirect()->back()->with('info', "Bukti transfer booking {$booking->booking_code} ditolak. Wisatawan dapat mengunggah ulang struk yang sah.");
        });
    }

    /**
     * Menyajikan berkas bukti transfer privat khusus untuk Admin Paguyuban
     */
    public function viewProof(int $id, int $paymentId)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak. Berkas bukti pembayaran hanya dapat diakses oleh Admin Paguyuban.');
        }

        $payment = Payment::where('id', $paymentId)->where('booking_id', $id)->firstOrFail();

        if (!$payment->proof_image || !Storage::disk('local')->exists($payment->proof_image)) {
            abort(404, 'Berkas fisik bukti transfer tidak ditemukan di penyimpanan server.');
        }

        return Storage::disk('local')->response($payment->proof_image);
    }
}
