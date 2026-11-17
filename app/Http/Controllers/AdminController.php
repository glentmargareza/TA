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
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $totalRevenue = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])->sum('paid_amount');
        $totalCustomersCount = Booking::distinct('customer_phone')->count('customer_phone');

        // Monitoring Armada Jeep Counts
        $readyJeepsCount = Jeep::where('status', 'READY')->count();
        $onTripJeepsCount = Jeep::where('status', 'ON_TRIP')->count();
        $maintenanceJeepsCount = Jeep::where('status', 'MAINTENANCE')->count();
        $totalJeepsCount = Jeep::count();

        // Statistik Operasional Booking Riil
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // 1. Total Booking Hari Ini & Perbandingan Kemarin
        $todayBookingsCount = Booking::where(function ($q) use ($today) {
            $q->whereDate('trip_date', $today)->orWhereDate('created_at', $today);
        })->where('booking_status', '!=', 'CANCELLED')->count();

        $yesterdayBookingsCount = Booking::where(function ($q) use ($yesterday) {
            $q->whereDate('trip_date', $yesterday)->orWhereDate('created_at', $yesterday);
        })->where('booking_status', '!=', 'CANCELLED')->count();

        if ($yesterdayBookingsCount > 0) {
            $todayPercentChange = (int) round((($todayBookingsCount - $yesterdayBookingsCount) / $yesterdayBookingsCount) * 100);
        } else {
            $todayPercentChange = $todayBookingsCount > 0 ? 100 : 0;
        }

        // 2. Booking Mendatang (Jadwal trip setelah hari ini)
        $upcomingBookingsCount = Booking::whereDate('trip_date', '>', $today)
            ->whereIn('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID'])
            ->count();

        // 3. Booking Selesai (Trip telah lewat atau status selesai)
        $completedBookingsCount = Booking::where('booking_status', 'COMPLETED')
            ->orWhere(function ($q) use ($today) {
                $q->whereDate('trip_date', '<', $today)
                    ->whereIn('booking_status', ['FULL_PAID', 'DP_PAID']);
            })
            ->count();

        // 4. Booking Dibatalkan
        $cancelledBookingsCount = Booking::where('booking_status', 'CANCELLED')->count();

        // Status Distribusi Pembayaran
        $allBookingsCount = Booking::count();
        $paidCount = Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID', 'COMPLETED'])->count();
        $pendingCount = Booking::where('booking_status', 'PENDING')->count();
        $cancelCount = $cancelledBookingsCount;

        if ($allBookingsCount > 0) {
            $paidPercentage = (int) round(($paidCount / $allBookingsCount) * 100);
            $pendingPercentage = (int) round(($pendingCount / $allBookingsCount) * 100);
            $cancelPercentage = max(0, 100 - $paidPercentage - $pendingPercentage);

            $upcomingPercentage = (int) round(($upcomingBookingsCount / $allBookingsCount) * 100);
            $completedPercentage = (int) round(($completedBookingsCount / $allBookingsCount) * 100);
        } else {
            $paidPercentage = 0;
            $pendingPercentage = 0;
            $cancelPercentage = 0;
            $upcomingPercentage = 0;
            $completedPercentage = 0;
        }

        // Data Grafik Omzet & Booking (Dukungan Rentang 7, 14, dan 30 Hari Terakhir)
        $chartData = [
            '7' => ['days' => [], 'revenue' => [], 'bookings' => []],
            '14' => ['days' => [], 'revenue' => [], 'bookings' => []],
            '30' => ['days' => [], 'revenue' => [], 'bookings' => []],
        ];

        for ($i = 29; $i >= 0; $i--) {
            $dt = now()->subDays($i);
            $dateStr = $dt->toDateString();
            $dayLabel = $dt->translatedFormat('d M');

            $rev = (int) Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID'])
                ->whereDate('created_at', $dateStr)
                ->sum('paid_amount');

            $bCount = (int) Booking::whereDate('created_at', $dateStr)->count();

            if ($i < 7) {
                $chartData['7']['days'][] = $dayLabel;
                $chartData['7']['revenue'][] = $rev;
                $chartData['7']['bookings'][] = $bCount;
            }
            if ($i < 14) {
                $chartData['14']['days'][] = $dayLabel;
                $chartData['14']['revenue'][] = $rev;
                $chartData['14']['bookings'][] = $bCount;
            }
            $chartData['30']['days'][] = $dayLabel;
            $chartData['30']['revenue'][] = $rev;
            $chartData['30']['bookings'][] = $bCount;
        }

        $chartDays = $chartData['7']['days'];
        $chartRevenue = $chartData['7']['revenue'];
        $chartBookings = $chartData['7']['bookings'];

        // 5 Booking Terkini untuk Tampilan Widget Cepat
        $recentBookings = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver', 'payments'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Notifikasi dan Riwayat Aktivitas
        $recentActivities = ActivityLog::with('user')->orderBy('id', 'desc')->take(4)->get();
        $unreadNotificationsCount = ActivityLog::unread()->count();
        $notifications = ActivityLog::orderBy('id', 'desc')->take(10)->get();

        // Foto Background Beranda untuk Scenic Header Banner
        $settingsFile = storage_path('app/basecamp_settings.json');
        $heroBg = null;
        if (file_exists($settingsFile)) {
            $basecampSettings = json_decode(file_get_contents($settingsFile), true);
            if (!empty($basecampSettings['hero_background'])) {
                $heroBg = str_starts_with($basecampSettings['hero_background'], 'http')
                    ? $basecampSettings['hero_background']
                    : asset($basecampSettings['hero_background']);
            }
        }
        if (!$heroBg) {
            $heroBg = asset('img/beranda.jpeg');
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalCustomersCount',
            'readyJeepsCount',
            'onTripJeepsCount',
            'maintenanceJeepsCount',
            'totalJeepsCount',
            'todayBookingsCount',
            'yesterdayBookingsCount',
            'todayPercentChange',
            'upcomingBookingsCount',
            'upcomingPercentage',
            'completedBookingsCount',
            'completedPercentage',
            'cancelledBookingsCount',
            'paidCount',
            'pendingCount',
            'cancelCount',
            'allBookingsCount',
            'recentBookings',
            'chartDays',
            'chartRevenue',
            'chartBookings',
            'chartData',
            'paidPercentage',
            'pendingPercentage',
            'cancelPercentage',
            'recentActivities',
            'unreadNotificationsCount',
            'notifications',
            'heroBg'
        ));
    }

    /**
     * Halaman Dedicated: Manajemen Booking Wisatawan
     */
    public function bookings(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $search = $request->query('search');
        $tripDate = $request->query('trip_date');
        $packageId = $request->query('package_id');
        $status = $request->query('status', 'ALL');

        // Pastikan seluruh booking berstatus terbayar (DP/Lunas) telah otomatis dialokasikan driver & armada jeep
        \App\Services\BookingAssignmentService::autoAssignUnassignedPaidBookings();

        $query = Booking::with(['package', 'slot', 'pickupZone', 'assignments.jeep', 'assignments.driver', 'payments']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if (!empty($tripDate)) {
            $query->whereDate('trip_date', $tripDate);
        }

        if (!empty($packageId) && $packageId !== 'ALL') {
            $query->where('package_id', $packageId);
        }

        if (!empty($status) && $status !== 'ALL') {
            $query->where('booking_status', $status);
        }

        $bookings = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $packages = Package::all();
        $jeeps = Jeep::all();
        $drivers = Driver::where('is_active', true)->get();
        $slots = ScheduleSlot::where('is_active', true)->orderBy('start_time')->get();
        $unreadNotificationsCount = ActivityLog::unread()->count();

        return view('admin.bookings', compact(
            'bookings',
            'packages',
            'jeeps',
            'drivers',
            'slots',
            'search',
            'tripDate',
            'packageId',
            'status',
            'unreadNotificationsCount'
        ));
    }

    /**
     * Halaman Dedicated: Monitoring Armada Jeep
     */
    public function armada(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $readyJeepsCount = Jeep::where('status', 'READY')->count();
        $onTripJeepsCount = Jeep::where('status', 'ON_TRIP')->count();
        $maintenanceJeepsCount = Jeep::where('status', 'MAINTENANCE')->count();
        $totalJeepsCount = Jeep::count();

        $jeepsMonitoring = Jeep::with(['assignments' => function ($q) {
            $q->whereHas('booking', function ($b) {
                $b->whereIn('booking_status', ['FULL_PAID', 'DP_PAID']);
            })->with(['booking.package', 'booking.slot'])->latest();
        }])->get();

        // Data Driver Paguyuban (Aktif & Nonaktif)
        $activeDrivers = Driver::where('is_active', true)->orderBy('id')->get();
        $inactiveDrivers = Driver::where('is_active', false)->orderBy('id')->get();
        $totalDriversCount = Driver::count();
        $activeDriversCount = $activeDrivers->count();
        $inactiveDriversCount = $inactiveDrivers->count();

        $unreadNotificationsCount = ActivityLog::unread()->count();

        return view('admin.armada', compact(
            'readyJeepsCount',
            'onTripJeepsCount',
            'maintenanceJeepsCount',
            'totalJeepsCount',
            'jeepsMonitoring',
            'activeDrivers',
            'inactiveDrivers',
            'totalDriversCount',
            'activeDriversCount',
            'inactiveDriversCount',
            'unreadNotificationsCount'
        ));
    }

    /**
     * Halaman Dedicated: Notifikasi & Aktivitas Operasional
     */
    public function notifications(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban untuk membuka portal ini.');
        }

        $type = $request->query('type', 'ALL');
        $query = ActivityLog::with('user')->orderBy('id', 'desc');

        if ($type !== 'ALL' && !empty($type)) {
            $query->where('type', $type);
        }

        $activities = $query->paginate(20)->withQueryString();
        $unreadNotificationsCount = ActivityLog::unread()->count();
        $totalActivities = ActivityLog::count();
        $typeCounts = ActivityLog::selectRaw('type, count(*) as total')->groupBy('type')->pluck('total', 'type')->toArray();

        return view('admin.notifications', compact(
            'activities',
            'type',
            'unreadNotificationsCount',
            'totalActivities',
            'typeCounts'
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
            'cover_image' => 'nullable|string|max:500',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'description' => 'required|string',
            'routes_raw' => 'nullable|string',
        ]);

        $routesList = [];
        if (!empty($validated['routes_raw'])) {
            $routesList = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['routes_raw']))));
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Handle cover image upload or URL fallback
        $coverImage = $validated['cover_image'] ?? 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1000&q=80';
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $fileName = 'package_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/packages');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $coverImage = 'uploads/packages/' . $fileName;
        }

        Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'price_per_jeep' => $validated['price_per_jeep'],
            'duration_minutes' => $validated['duration_minutes'],
            'duration_text' => $validated['duration_text'] ?? ($validated['duration_minutes'] . ' Menit'),
            'destinations_count' => count($routesList),
            'routes_list' => $routesList,
            'category_badge' => $validated['category_badge'] ?? 'Paket Pilihan',
            'cover_image' => $coverImage,
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
            'cover_image' => 'nullable|string|max:500',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'description' => 'required|string',
            'routes_raw' => 'nullable|string',
        ]);

        $routesList = [];
        if (!empty($validated['routes_raw'])) {
            $routesList = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['routes_raw']))));
        }

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Determine cover image (file upload takes precedence over text input)
        $coverImage = $package->getRawOriginal('cover_image');
        if ($request->hasFile('cover_image_file')) {
            $file = $request->file('cover_image_file');
            $fileName = 'package_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/packages');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $coverImage = 'uploads/packages/' . $fileName;

            // Remove previous uploaded image if not default asset
            $oldRaw = $package->getRawOriginal('cover_image');
            if ($oldRaw && str_starts_with($oldRaw, 'uploads/packages/')) {
                $oldFile = public_path($oldRaw);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
        } elseif ($request->filled('cover_image')) {
            $coverImage = $validated['cover_image'];
        } elseif ($request->has('cover_image') && empty($request->input('cover_image'))) {
            $coverImage = null;
        }

        $package->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'price_per_jeep' => $validated['price_per_jeep'],
            'duration_minutes' => $validated['duration_minutes'],
            'duration_text' => $validated['duration_text'] ?? $package->duration_text,
            'destinations_count' => count($routesList) > 0 ? count($routesList) : $package->destinations_count,
            'routes_list' => count($routesList) > 0 ? $routesList : $package->routes_list,
            'category_badge' => $validated['category_badge'] ?? $package->category_badge,
            'cover_image' => $coverImage,
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
            'max_jeeps' => 'nullable|integer|min:1|max:50',
        ]);

        ScheduleSlot::create([
            'slot_label' => $validated['slot_label'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_jeeps' => $validated['max_jeeps'] ?? 20,
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
            'max_jeeps' => 'nullable|integer|min:1|max:50',
        ]);

        $slot->update([
            'slot_label' => $validated['slot_label'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_jeeps' => $validated['max_jeeps'] ?? $slot->max_jeeps ?? 20,
        ]);

        return redirect()->back()->with('success', "Jadwal sesi {$slot->slot_label} berhasil diperbarui!");
    }

    public function toggleSchedule(Request $request, int $id)
    {
        $slot = ScheduleSlot::findOrFail($id);
        $slot->is_active = !$slot->is_active;
        $slot->save();

        $status = $slot->is_active ? 'Diaktifkan (Siaga)' : 'Dinonaktifkan (Libur)';
        $message = "Status sesi {$slot->slot_label} berhasil {$status}!";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $slot->is_active,
                'status_label' => $slot->is_active ? 'Buka (Aktif)' : 'Tutup (Libur)',
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
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
        $booking = Booking::findOrFail($id);
        $remaining = (float) $booking->remaining_amount;

        DB::transaction(function () use ($booking, $remaining) {
            Payment::create([
                'booking_id' => $booking->id,
                'order_id' => 'CASH-' . $booking->booking_code . '-' . time(),
                'transaction_ref' => 'CASH-BASECAMP',
                'payment_stage' => 'SETTLEMENT',
                'gross_amount' => $remaining,
                'payment_type' => 'CASH_BASECAMP',
                'payment_status' => 'SETTLED',
                'paid_at' => now(),
            ]);

            $booking->update([
                'booking_status' => 'FULL_PAID',
                'paid_amount' => $booking->total_amount,
                'remaining_amount' => 0.00,
            ]);

            // Otomatis alokasikan Driver dan Armada Jeep saat pelunasan di loket
            \App\Services\BookingAssignmentService::autoAssign($booking);

            // Notifikasi & Riwayat Aktivitas: Pembayaran Berhasil & Aksi Admin
            ActivityLog::logPaymentSuccess($booking, $remaining, 'Loket Kasir Basecamp');
            ActivityLog::logAdmin(
                'Pelunasan Sisa Tagihan di Loket',
                "Menerima pelunasan sisa tagihan Rp " . number_format($remaining, 0, ',', '.') . " untuk booking #{$booking->booking_code} ({$booking->customer_name}).",
                'payment',
                route('admin.dashboard', ['search' => $booking->booking_code])
            );
        });

        return redirect()->back()->with('success', "Pelunasan untuk booking {$booking->booking_code} berhasil diterima!");
    }

    public function assignDriver(Request $request, int $id)
    {
        // 1. Driver dialokasikan dari pilihan admin, atau auto-alokasi jika 'random' / kosong
        if (!$request->filled('driver_id') || $request->input('driver_id') === 'random') {
            $autoDriver = Driver::where('is_active', true)
                ->withCount('assignments')
                ->orderBy('assignments_count', 'asc')
                ->inRandomOrder()
                ->first()
                ?? Driver::first();
            $request->merge(['driver_id' => $autoDriver?->id]);
        }

        // 2. Armada Jeep dikelola otomatis oleh sistem (admin hanya menentukan Driver)
        if (!$request->filled('jeep_id') || $request->input('jeep_id') === 'random') {
            $existingAssign = BookingAssignment::where('booking_id', $id)->first();
            $jeepId = $existingAssign?->jeep_id;
            if (!$jeepId) {
                $randomJeep = Jeep::where('status', 'READY')->inRandomOrder()->first() ?? Jeep::inRandomOrder()->first();
                $jeepId = $randomJeep?->id;
            }
            $request->merge(['jeep_id' => $jeepId]);
        }

        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'jeep_id'   => 'nullable|exists:jeeps,id',
        ]);

        BookingAssignment::updateOrCreate(
            ['booking_id' => $id],
            [
                'jeep_id'     => $validated['jeep_id'],
                'driver_id'   => $validated['driver_id'],
                'assigned_at' => now(),
            ]
        );

        $jeep = Jeep::find($validated['jeep_id']);
        $driver = Driver::find($validated['driver_id']);
        $booking = Booking::find($id);

        if ($booking && $booking->trip_date?->isToday()) {
            $jeep?->update(['status' => 'ON_TRIP']);
        }

        // Riwayat Aktivitas: Penugasan Driver
        ActivityLog::logAdmin(
            'Penugasan Driver',
            "Menugaskan Driver Paguyuban: {$driver?->full_name} untuk booking #{$booking?->booking_code} ({$booking?->customer_name}).",
            'admin',
            route('admin.dashboard', ['search' => $booking?->booking_code])
        );

        return redirect()->back()->with('success', "Driver {$driver?->full_name} berhasil ditugaskan!");
    }

    /**
     * Daftarkan unit kendaraan / armada Jeep dan Driver baru ke dalam sistem
     */
    /**
     * Daftarkan unit kendaraan / armada Jeep dan Driver baru ke dalam sistem
     */
    public function storeJeep(Request $request)
    {
        // 1. Jika nomor plat kosong, otomatis gunakan nomor urut berikutnya mulai dari 1
        $numericPlates = Jeep::pluck('plate_number')->filter(fn($p) => is_numeric($p))->map(fn($p) => (int)$p);
        $nextNumber = $numericPlates->isNotEmpty() ? $numericPlates->max() + 1 : (Jeep::count() + 1);

        if (!$request->filled('plate_number')) {
            $request->merge(['plate_number' => (string) $nextNumber]);
        }

        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:jeeps,plate_number',
            'driver_name' => 'required|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
            'model_name' => 'nullable|string|max:100',
            'status' => 'required|in:READY,MAINTENANCE',
        ], [
            'plate_number.unique' => 'Nomor unit / plat tersebut sudah terdaftar pada armada lain.',
            'driver_name.required' => 'Nama driver wajib diisi.',
            'status.required' => 'Status awal armada wajib dipilih.',
        ]);

        $driverName = trim($validated['driver_name']);
        $plateNumber = trim($validated['plate_number']);
        $status = $validated['status'];
        $isActive = ($status === 'READY');

        // 2. Tambahkan driver baru ke tabel drivers (selalu sinkron 1:1)
        $phone = !empty($validated['driver_phone']) 
            ? trim($validated['driver_phone']) 
            : '0812' . str_pad((string) (Driver::count() + 1), 8, '0', STR_PAD_LEFT);

        $driver = Driver::create([
            'full_name' => $driverName,
            'phone_number' => $phone,
            'license_number' => 'SIM-B1-' . (1000 + Driver::count() + 1),
            'is_active' => $isActive,
        ]);

        // 3. Susun model_name armada: Jeep #<No> (<Nama Driver>)
        $modelName = !empty($validated['model_name']) 
            ? trim($validated['model_name']) 
            : "Jeep #{$plateNumber} ({$driverName})";

        $jeep = Jeep::create([
            'plate_number' => $plateNumber,
            'model_name' => $modelName,
            'status' => $status,
        ]);

        ActivityLog::logAdmin(
            'Penambahan Armada & Driver',
            "Menambahkan unit armada baru: {$jeep->model_name} (No: {$jeep->plate_number}) bersama driver {$driverName}.",
            'admin',
            route('admin.armada')
        );

        return redirect()->back()->with('success', "Armada {$jeep->model_name} dan Driver {$driverName} berhasil ditambahkan ke Paguyuban!");
    }

    /**
     * Update status operasional armada Jeep (READY / ON_TRIP / MAINTENANCE)
     */
    public function updateJeepStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:READY,ON_TRIP,MAINTENANCE',
        ]);

        $jeep = Jeep::findOrFail($id);
        $jeep->update(['status' => $validated['status']]);

        // Sinkronkan status driver pasangannya
        preg_match('/\((.*?)\)/', $jeep->model_name, $m);
        $driverName = $m[1] ?? null;
        if ($driverName) {
            $driver = Driver::where('full_name', 'like', "%{$driverName}%")->first();
            if ($driver) {
                $driver->update(['is_active' => in_array($validated['status'], ['READY', 'ON_TRIP'])]);
            }
        }

        $statusLabel = [
            'READY' => 'Tersedia (Ready Pangkalan)',
            'ON_TRIP' => 'Sedang Digunakan (On Trip)',
            'MAINTENANCE' => 'Tidak Tersedia (Perbaikan/Servis)',
        ][$validated['status']] ?? $validated['status'];

        // Riwayat Aktivitas: Perubahan Status Jeep
        ActivityLog::logAdmin(
            'Pembaruan Status Armada',
            "Memperbarui status {$jeep->model_name} menjadi {$statusLabel}.",
            'admin',
            route('admin.dashboard')
        );

        return redirect()->back()->with('success', "Status {$jeep->model_name} berhasil diperbarui: {$statusLabel}!");
    }

    /**
     * Aktifkan seluruh armada Jeep menjadi TERSEDIA (READY)
     */
    public function activateAllJeeps()
    {
        Jeep::query()->update(['status' => 'READY']);
        Driver::query()->update(['is_active' => true]);

        ActivityLog::logAdmin(
            'Aktivasi Seluruh Armada & Driver',
            'Mengaktifkan seluruh armada Jeep dan personel Driver Paguyuban menjadi status TERSEDIA (Ready).',
            'admin',
            route('admin.armada')
        );

        return redirect()->back()->with('success', 'Seluruh unit armada Jeep dan personel Driver Paguyuban berhasil diaktifkan menjadi TERSEDIA (Ready)!');
    }

    /**
     * Ubah status driver (Aktif / Nonaktif) - Selalu sinkron dengan status unit Jeep
     */
    public function toggleDriverStatus(Request $request, int $id)
    {
        $driver = Driver::findOrFail($id);
        $newStatus = !$driver->is_active;
        $driver->update(['is_active' => $newStatus]);

        // Sinkronkan status unit Jeep pasangannya
        $newJeepStatus = $newStatus ? 'READY' : 'MAINTENANCE';
        $jeep = Jeep::where('model_name', 'like', "%({$driver->full_name})%")->first()
            ?? Jeep::where('model_name', 'like', "%{$driver->full_name}%")->first();

        if (!$jeep) {
            $allDrivers = Driver::orderBy('id')->pluck('id')->toArray();
            $idx = array_search($id, $allDrivers);
            if ($idx !== false) {
                $allJeeps = Jeep::orderBy('id')->get();
                if (isset($allJeeps[$idx])) {
                    $jeep = $allJeeps[$idx];
                }
            }
        }

        if ($jeep && $jeep->status !== 'ON_TRIP') {
            $jeep->update(['status' => $newJeepStatus]);
        }

        $statusText = $newStatus ? 'AKTIF (Siap Jalan & Unit Ready)' : 'NONAKTIF (Libur / Izin & Unit Servis)';

        ActivityLog::logAdmin(
            'Pembaruan Status Driver & Armada',
            "Mengubah status driver {$driver->full_name} beserta armada Jeep pasangannya menjadi {$statusText}.",
            'admin',
            route('admin.armada')
        );

        return redirect()->back()->with('success', "Status driver {$driver->full_name} dan armada Jeep pasangannya berhasil diubah menjadi {$statusText}!");
    }

    /**
     * Hapus data driver dari sistem paguyuban - Selalu sinkron menghapus armada Jeep pasangannya
     */
    public function destroyDriver(int $id)
    {
        $driver = Driver::findOrFail($id);
        $name = $driver->full_name;

        // Cek apakah driver memiliki riwayat penugasan booking
        if ($driver->assignments()->exists()) {
            return redirect()->back()->withErrors([
                "Driver {$name} tidak dapat dihapus karena memiliki riwayat penugasan transaksi booking. Silakan gunakan tombol 'Nonaktifkan' agar driver tidak lagi ditugaskan pada trip baru."
            ]);
        }

        // Cari armada Jeep yang berpasangan dengan driver ini
        $jeep = Jeep::where('model_name', 'like', "%({$name})%")->first()
            ?? Jeep::where('model_name', 'like', "%{$name}%")->first();

        if (!$jeep && Jeep::count() > 0) {
            $allDrivers = Driver::orderBy('id')->pluck('id')->toArray();
            $driverIdx = array_search($id, $allDrivers);
            if ($driverIdx !== false) {
                $allJeeps = Jeep::orderBy('id')->get();
                if (isset($allJeeps[$driverIdx])) {
                    $candidateJeep = $allJeeps[$driverIdx];
                    if (!$candidateJeep->assignments()->exists()) {
                        $jeep = $candidateJeep;
                    }
                }
            }
        }

        if ($jeep && $jeep->assignments()->exists()) {
            return redirect()->back()->withErrors([
                "Armada {$jeep->model_name} memiliki riwayat penugasan transaksi booking sehingga tidak dapat dihapus."
            ]);
        }

        $driver->delete();
        if ($jeep) {
            $jeep->delete();
        }

        ActivityLog::logAdmin(
            'Penghapusan Driver & Armada',
            "Menghapus data driver {$name} beserta unit armada Jeep pasangannya dari daftar paguyuban.",
            'admin',
            route('admin.armada')
        );

        return redirect()->back()->with('success', "Driver {$name} beserta unit armada Jeep pasangannya berhasil dihapus dari sistem!");
    }

    /**
     * Hapus unit armada Jeep dari sistem - Selalu sinkron menghapus driver pasangannya
     */
    public function destroyJeep(int $id)
    {
        $jeep = Jeep::findOrFail($id);
        $name = $jeep->model_name;

        if ($jeep->assignments()->exists()) {
            return redirect()->back()->withErrors([
                "Armada {$name} tidak dapat dihapus karena memiliki riwayat penugasan transaksi booking."
            ]);
        }

        preg_match('/\((.*?)\)/', $name, $matches);
        $driverName = $matches[1] ?? null;
        $driver = null;
        if ($driverName) {
            $driver = Driver::where('full_name', 'like', "%{$driverName}%")->first();
        }

        if ($driver && $driver->assignments()->exists()) {
            return redirect()->back()->withErrors([
                "Driver {$driver->full_name} memiliki riwayat transaksi booking sehingga tidak dapat dihapus."
            ]);
        }

        $jeep->delete();
        $driver?->delete();

        ActivityLog::logAdmin(
            'Penghapusan Armada & Driver',
            "Menghapus unit armada: {$name} beserta driver pasangannya dari daftar paguyuban.",
            'admin',
            route('admin.armada')
        );

        return redirect()->back()->with('success', "Armada {$name} berhasil dihapus dari sistem!");
    }

    /**
     * Batalkan Reservasi / Booking
     */
    public function cancelBooking(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['booking_status' => 'CANCELLED']);

        // Bebaskan penugasan armada jika ada
        BookingAssignment::where('booking_id', $id)->delete();

        // Notifikasi & Riwayat Aktivitas: Pembatalan Booking
        ActivityLog::logBookingCancelled($booking, 'Admin Loket');

        return redirect()->back()->with('success', "Booking #{$booking->booking_code} berhasil dibatalkan.");
    }

    /**
     * Ubah Jadwal Booking (Reschedule untuk Menyelesaikan Bentrok / Cuaca / Permintaan Wisatawan)
     */
    public function rescheduleBooking(Request $request, int $id)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('info', 'Silakan masuk dengan akun Admin Paguyuban.');
        }

        $booking = Booking::with(['package', 'slot', 'assignments.jeep', 'assignments.driver'])->findOrFail($id);

        $validated = $request->validate([
            'trip_date'  => 'required|date',
            'slot_id'    => 'required|exists:schedule_slots,id',
            'reason'     => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:500',
            'force'      => 'nullable|boolean',
        ]);

        $newSlot = ScheduleSlot::findOrFail($validated['slot_id']);
        $newTripDate = $validated['trip_date'];
        $oldTripDate = $booking->trip_date ? $booking->trip_date->format('Y-m-d') : '-';
        $oldSlotLabel = $booking->slot ? ($booking->slot->slot_label . ' (' . $booking->slot->start_time . ' - ' . $booking->slot->end_time . ' WIB)') : '-';

        // Cek apakah ada perubahan
        if ($oldTripDate === $newTripDate && (int)$booking->slot_id === (int)$validated['slot_id']) {
            return redirect()->back()->with('info', "Jadwal booking {$booking->booking_code} tidak berubah (tanggal dan sesi waktu sama).");
        }

        // Cek bentrok kuota slot target (kecuali jika admin memaksa/force override paguyuban)
        $isSameSlotAndDate = ($oldTripDate === $newTripDate && (int)$booking->slot_id === (int)$validated['slot_id']);
        if (!$isSameSlotAndDate) {
            $bookedJeeps = $newSlot->bookedJeepsCount($newTripDate);
            $remaining = max(0, $newSlot->max_jeeps - $bookedJeeps);

            if ($booking->jeep_quantity > $remaining && !$request->boolean('force')) {
                return redirect()->back()->with('error', "Bentrok Jadwal / Kuota Penuh: Sesi {$newSlot->slot_label} pada tanggal " . date('d/m/Y', strtotime($newTripDate)) . " hanya tersisa {$remaining} Jeep (booking ini butuh {$booking->jeep_quantity} Jeep). Silakan pilih sesi lain atau centang 'Tetap Jadwalkan (Override Kuota Paguyuban)'.");
            }
        }

        $reason = $validated['reason'] ?? 'Penyelesaian Bentrok / Reschedule';
        $note = $validated['admin_note'] ?? null;

        DB::transaction(function () use ($booking, $newTripDate, $newSlot, $oldTripDate, $oldSlotLabel, $reason, $note, $user) {
            $booking->trip_date = $newTripDate;
            $booking->slot_id = $newSlot->id;

            // Catat log reschedule ke customer_notes untuk jejak audit
            $rescheduleNote = "[Reschedule: " . date('d/m/Y H:i') . " oleh " . ($user->name ?? 'Admin') . " - {$reason}";
            if ($note) {
                $rescheduleNote .= " ({$note})";
            }
            $rescheduleNote .= "]";

            if (!empty($booking->customer_notes)) {
                $booking->customer_notes .= "\n" . $rescheduleNote;
            } else {
                $booking->customer_notes = $rescheduleNote;
            }

            $booking->save();

            // Perbarui status armada Jeep jika tanggal dipindahkan ke atau dari hari ini
            $assignment = $booking->assignments->first();
            if ($assignment && $assignment->jeep) {
                if ($booking->trip_date->isToday()) {
                    $assignment->jeep->update(['status' => 'ON_TRIP']);
                } else {
                    if ($assignment->jeep->status === 'ON_TRIP') {
                        $hasOtherTripToday = BookingAssignment::where('jeep_id', $assignment->jeep_id)
                            ->where('booking_id', '!=', $booking->id)
                            ->whereHas('booking', function ($q) {
                                $q->whereDate('trip_date', now()->toDateString())
                                  ->whereIn('booking_status', ['DP_PAID', 'FULL_PAID']);
                            })->exists();

                        if (!$hasOtherTripToday) {
                            $assignment->jeep->update(['status' => 'READY']);
                        }
                    }
                }
            }

            // Catat riwayat aktivitas admin & notifikasi sistem
            ActivityLog::logAdmin(
                'Perubahan Jadwal Booking (Reschedule)',
                "Mengubah jadwal booking #{$booking->booking_code} ({$booking->customer_name}) dari {$oldTripDate} ({$oldSlotLabel}) menjadi {$newTripDate} sesi {$newSlot->slot_label} ({$newSlot->start_time} - {$newSlot->end_time} WIB). Alasan: {$reason}.",
                'schedule',
                route('admin.bookings', ['search' => $booking->booking_code])
            );
        });

        return redirect()->back()->with('success', "Jadwal booking {$booking->booking_code} berhasil diubah ke tanggal " . date('d F Y', strtotime($newTripDate)) . " ({$newSlot->slot_label})!");
    }

    /**
     * Cek Ketersediaan Kuota Sesi Jadwal Secara Dinamis (AJAX / Realtime untuk Modal Reschedule)
     */
    public function checkSlotAvailability(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $excludeBookingId = (int) $request->query('exclude_booking_id', 0);

        $slots = ScheduleSlot::where('is_active', true)->orderBy('start_time')->get();

        $data = $slots->map(function ($s) use ($date, $excludeBookingId) {
            $query = $s->bookings()
                ->whereDate('trip_date', $date)
                ->whereIn('booking_status', ['PENDING', 'DP_PAID', 'FULL_PAID']);

            if ($excludeBookingId > 0) {
                $query->where('id', '!=', $excludeBookingId);
            }

            $booked = (int) $query->sum('jeep_quantity');
            $remaining = max(0, $s->max_jeeps - $booked);

            return [
                'id' => $s->id,
                'slot_label' => $s->slot_label,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
                'time_range' => $s->start_time . ' - ' . $s->end_time . ' WIB',
                'max_jeeps' => $s->max_jeeps,
                'booked_jeeps' => $booked,
                'remaining_jeeps' => $remaining,
                'is_full' => $remaining <= 0,
            ];
        });

        return response()->json([
            'date' => $date,
            'slots' => $data,
        ]);
    }

    /**
     * Tandai Semua Notifikasi Sudah Dibaca
     */
    public function markAllNotificationsRead(Request $request)
    {
        ActivityLog::unread()->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * Halaman Pengaturan Sistem & Operasional Basecamp Paguyuban
     */
    public function settings(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $settingsFile = storage_path('app/basecamp_settings.json');
        $basecamp = [
            'name' => 'Paguyuban Jeep Wukirsari Adventure',
            'basecamp_address' => 'Jl. Bebeng - Cangkringan, Wukirsari, Kapanewon Cangkringan, Kabupaten Sleman, D.I. Yogyakarta 55583',
            'cs_phone' => '+62 812-3456-7890',
            'operational_hours' => '06:00 - 18:00 WIB',
            'dp_percentage' => 30,
            'max_pax_per_jeep' => 4,
            'checkin_lead_minutes' => 30,
            'google_maps_url' => 'https://maps.google.com/?q=-7.6475,110.4560',
            'hero_badge' => 'Offroad Lereng Merapi',
            'hero_headline' => 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari',
            'hero_subtitle' => 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.',
            'cancellation_terms' => 'Pembatalan maksimal H-1 sebelum jam keberangkatan dengan pengembalian dana DP 50% atau perubahan jadwal gratis 1x.',
            'payment_channel' => 'Midtrans Payment Gateway (QRIS, VA, E-Wallet)',
            'qris_info' => 'QRIS Dinamis Otomatis via Midtrans / QRIS Statis Loket Pangkalan',
            'bank_account_info' => 'Bank Mandiri: 137-00-1234567-8 a/n Paguyuban Jeep Wukirsari',
            'hero_background' => null,
            'hero_location_0' => 'Susur Kali Gendol',
            'hero_slide_1' => null,
            'hero_location_1' => 'Spot Sunrise',
            'hero_slide_2' => null,
            'hero_location_2' => 'Jalur Offroad',
            'hero_slide_3' => null,
            'hero_location_3' => 'Museum Kampung Hilang',
            'hero_slide_4' => null,
            'hero_location_4' => 'Manuver Air',
            'gallery_title' => 'Jelajahi Momen Indah Bersama Kami',
            'gallery_subtitle' => 'Kumpulan foto perjalanan seru di berbagai rute wisata.',
            'gallery_image_1' => null,
            'gallery_caption_1' => 'Petualangan Jeep Merapi',
            'gallery_image_2' => null,
            'gallery_caption_2' => 'Sunrise Tour Merapi',
            'gallery_image_3' => null,
            'gallery_caption_3' => 'Bunker Kaliadem Merapi',
            'gallery_image_4' => null,
            'gallery_caption_4' => 'Rute Kali Kuning Offroad',
            'gallery_image_5' => null,
            'gallery_caption_5' => 'Lembah Panorama Merapi',
            'notify_booking_new' => true,
            'notify_payment_success' => true,
            'notify_cancellation' => true,
            'notify_wa' => true,
        ];

        if (file_exists($settingsFile)) {
            $saved = json_decode(file_get_contents($settingsFile), true);
            if (is_array($saved)) {
                $basecamp = array_merge($basecamp, $saved);
            }
        }

        return view('admin.settings', compact('user', 'basecamp'));
    }

    /**
     * Simpan Perubahan Profil & Password Admin
     */
    public function updateAdminProfile(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.'])->withInput();
            }
            $user->password = Hash::make($validated['new_password']);
        }

        if (!empty($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (!empty($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        ActivityLog::logAdmin(
            'Pembaruan Akun Admin',
            "Admin memperbarui profil & kredensial akun ({$user->email}).",
            'admin',
            route('admin.settings')
        );

        return redirect()->back()->with('success', 'Profil dan kredensial akun admin berhasil diperbarui!');
    }

    /**
     * Simpan Konfigurasi Operasional Basecamp & Kebijakan Trip
     */
    public function updateBasecampSettings(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:150',
            'basecamp_address' => 'nullable|string|max:255',
            'cs_phone' => 'nullable|string|max:30',
            'operational_hours' => 'nullable|string|max:100',
            'dp_percentage' => 'nullable|numeric|min:10|max:100',
            'max_pax_per_jeep' => 'nullable|integer|min:1|max:6',
            'checkin_lead_minutes' => 'nullable|integer|min:10|max:120',
            'google_maps_url' => 'nullable|url|max:255',
            'hero_badge' => 'nullable|string|max:100',
            'hero_headline' => 'nullable|string|max:200',
            'hero_subtitle' => 'nullable|string|max:500',
            'cancellation_terms' => 'nullable|string|max:1000',
            'payment_channel' => 'nullable|string|max:150',
            'qris_info' => 'nullable|string|max:255',
            'bank_account_info' => 'nullable|string|max:255',
            'notify_booking_new' => 'nullable',
            'notify_payment_success' => 'nullable',
            'notify_cancellation' => 'nullable',
            'notify_wa' => 'nullable',
            // Hero Background fields
            'hero_background' => 'nullable|string|max:500',
            'hero_background_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_hero_background' => 'nullable',
            'hero_location_0' => 'nullable|string|max:100',
            'hero_slide_1' => 'nullable|string|max:500',
            'hero_slide_1_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_hero_slide_1' => 'nullable',
            'hero_location_1' => 'nullable|string|max:100',
            'hero_slide_2' => 'nullable|string|max:500',
            'hero_slide_2_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_hero_slide_2' => 'nullable',
            'hero_location_2' => 'nullable|string|max:100',
            'hero_slide_3' => 'nullable|string|max:500',
            'hero_slide_3_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_hero_slide_3' => 'nullable',
            'hero_location_3' => 'nullable|string|max:100',
            'hero_slide_4' => 'nullable|string|max:500',
            'hero_slide_4_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_hero_slide_4' => 'nullable',
            'hero_location_4' => 'nullable|string|max:100',
            // Gallery Beranda fields
            'gallery_title' => 'nullable|string|max:150',
            'gallery_subtitle' => 'nullable|string|max:255',
            'gallery_image_1' => 'nullable|string|max:1000',
            'gallery_image_1_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_gallery_image_1' => 'nullable',
            'gallery_caption_1' => 'nullable|string|max:100',
            'gallery_image_2' => 'nullable|string|max:1000',
            'gallery_image_2_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_gallery_image_2' => 'nullable',
            'gallery_caption_2' => 'nullable|string|max:100',
            'gallery_image_3' => 'nullable|string|max:1000',
            'gallery_image_3_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_gallery_image_3' => 'nullable',
            'gallery_caption_3' => 'nullable|string|max:100',
            'gallery_image_4' => 'nullable|string|max:1000',
            'gallery_image_4_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_gallery_image_4' => 'nullable',
            'gallery_caption_4' => 'nullable|string|max:100',
            'gallery_image_5' => 'nullable|string|max:1000',
            'gallery_image_5_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'reset_gallery_image_5' => 'nullable',
            'gallery_caption_5' => 'nullable|string|max:100',
        ]);

        $settingsFile = storage_path('app/basecamp_settings.json');
        $existing = [];
        if (file_exists($settingsFile)) {
            $existing = json_decode(file_get_contents($settingsFile), true) ?: [];
        }

        // Folder upload background
        $destinationPath = public_path('uploads/hero');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // 1. Hero Background Utama (Slide 0)
        if ($request->boolean('reset_hero_background')) {
            unset($existing['hero_background']);
        } elseif ($request->hasFile('hero_background_file')) {
            $file = $request->file('hero_background_file');
            $fileName = 'hero_bg_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $existing['hero_background'] = 'uploads/hero/' . $fileName;
        } elseif ($request->filled('hero_background')) {
            $existing['hero_background'] = $request->input('hero_background');
        }

        // 2. Extra Slides (1 sampai 4)
        for ($i = 1; $i <= 4; $i++) {
            $resetKey = "reset_hero_slide_{$i}";
            $fileKey = "hero_slide_{$i}_file";
            $textKey = "hero_slide_{$i}";
            if ($request->boolean($resetKey)) {
                unset($existing[$textKey]);
            } elseif ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = "hero_slide_{$i}_" . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $existing[$textKey] = 'uploads/hero/' . $fileName;
            } elseif ($request->filled($textKey)) {
                $existing[$textKey] = $request->input($textKey);
            }
        }

        // 3. Galeri Foto Beranda (Slide 1 sampai 5)
        $galleryDestination = public_path('uploads/gallery');
        if (!file_exists($galleryDestination)) {
            mkdir($galleryDestination, 0755, true);
        }

        for ($i = 1; $i <= 5; $i++) {
            $resetKey = "reset_gallery_image_{$i}";
            $fileKey = "gallery_image_{$i}_file";
            $textKey = "gallery_image_{$i}";
            $captionKey = "gallery_caption_{$i}";

            if ($request->boolean($resetKey)) {
                unset($existing[$textKey]);
            } elseif ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = "gallery_{$i}_" . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($galleryDestination, $fileName);
                $existing[$textKey] = 'uploads/gallery/' . $fileName;
            } elseif ($request->filled($textKey)) {
                $existing[$textKey] = $request->input($textKey);
            }

            if ($request->has($captionKey)) {
                $existing[$captionKey] = $request->input($captionKey);
            }
        }

        // Tangani boolean checkbox bila form notifikasi dikirimkan
        if ($request->has('is_notification_form')) {
            $validated['notify_booking_new'] = $request->boolean('notify_booking_new');
            $validated['notify_payment_success'] = $request->boolean('notify_payment_success');
            $validated['notify_cancellation'] = $request->boolean('notify_cancellation');
            $validated['notify_wa'] = $request->boolean('notify_wa');
        }

        // Bersihkan objek UploadedFile dari $validated sebelum simpan
        $cleanValidated = array_filter($validated, fn($val) => !($val instanceof \Illuminate\Http\UploadedFile));
        unset(
            $cleanValidated['hero_background_file'],
            $cleanValidated['hero_slide_1_file'],
            $cleanValidated['hero_slide_2_file'],
            $cleanValidated['hero_slide_3_file'],
            $cleanValidated['hero_slide_4_file'],
            $cleanValidated['gallery_image_1_file'],
            $cleanValidated['gallery_image_2_file'],
            $cleanValidated['gallery_image_3_file'],
            $cleanValidated['gallery_image_4_file'],
            $cleanValidated['gallery_image_5_file']
        );

        $merged = array_merge($existing, array_filter($cleanValidated, fn($val) => $val !== null));
        file_put_contents($settingsFile, json_encode($merged, JSON_PRETTY_PRINT));

        ActivityLog::logAdmin(
            'Pembaruan Pengaturan Basecamp',
            "Admin memperbarui konfigurasi sistem, operasional, atau kebijakan pangkalan.",
            'admin',
            route('admin.settings')
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
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

            // Otomatis alokasikan Driver dan Armada Jeep saat verifikasi transfer bank disetujui
            \App\Services\BookingAssignmentService::autoAssign($booking);

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

            // Otomatis alokasikan Driver dan Armada Jeep saat pembayaran tunai dikonfirmasi
            \App\Services\BookingAssignmentService::autoAssign($booking);

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

        $filePath = Storage::disk('local')->path($payment->proof_image ?? '');
        if (!$payment->proof_image || !file_exists($filePath)) {
            abort(404, 'Berkas fisik bukti transfer tidak ditemukan di penyimpanan server.');
        }

        return response()->file($filePath);
    }
}
