<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Halaman direktori seluruh ulasan wisatawan
     */
    public function index(Request $request)
    {
        $ratingFilter = $request->query('rating');
        $packageFilter = $request->query('package');
        $search = $request->query('search');

        $query = Review::with(['user', 'package'])->where('is_approved', true);

        if ($ratingFilter && in_array((int) $ratingFilter, [1, 2, 3, 4, 5])) {
            $query->where('rating', (int) $ratingFilter);
        }

        if ($packageFilter) {
            $query->whereHas('package', function ($q) use ($packageFilter) {
                $q->where('slug', $packageFilter);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $query->latest()->paginate(9)->withQueryString();
        $packages = \App\Models\Package::where('is_active', true)->get();

        $totalReviews = Review::where('is_approved', true)->count();
        $avgRating = Review::where('is_approved', true)->avg('rating') ?: 5.0;

        $selectedPackage = $packageFilter ? $packages->firstWhere('slug', $packageFilter) : null;

        $verifiedEmails = \App\Models\Booking::whereIn('booking_status', ['FULL_PAID', 'DP_PAID', 'SETTLED', 'COMPLETED', 'CONFIRMED'])
            ->pluck('customer_email')
            ->filter()
            ->unique()
            ->toArray();

        return view('reviews.index', compact(
            'reviews',
            'packages',
            'totalReviews',
            'avgRating',
            'ratingFilter',
            'packageFilter',
            'selectedPackage',
            'verifiedEmails',
            'search'
        ));
    }

    /**
     * Simpan ulasan wisatawan (Hanya untuk pengguna yang sudah login)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk ke akun Anda terlebih dahulu untuk memberikan ulasan.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:1000',
            'package_id' => 'nullable|exists:packages,id',
            'city' => 'nullable|string|max:100',
        ]);

        $userId = Auth::id();

        // Jika ulasan untuk paket tertentu, cek apakah sudah pernah memberi ulasan
        if (!empty($validated['package_id'])) {
            $existing = Review::where('user_id', $userId)
                ->where('package_id', $validated['package_id'])
                ->first();

            if ($existing) {
                $existing->update([
                    'rating' => (int) $validated['rating'],
                    'comment' => $validated['comment'],
                    'city' => !empty($validated['city']) ? trim($validated['city']) : $existing->city,
                ]);

                return redirect()->back()->with('success', 'Ulasan Anda untuk paket wisata ini berhasil diperbarui!');
            }
        }

        Review::create([
            'user_id' => $userId,
            'package_id' => $validated['package_id'] ?? null,
            'rating' => (int) $validated['rating'],
            'comment' => $validated['comment'],
            'city' => !empty($validated['city']) ? trim($validated['city']) : null,
            'is_approved' => true,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan dan penilaian Anda! Ulasan Anda telah berhasil dipublikasikan.');
    }

    /**
     * Perbarui ulasan wisatawan (Hanya pemilik atau admin)
     */
    public function update(Request $request, int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk mengubah ulasan.');
        }

        $review = Review::findOrFail($id);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->id !== $review->user_id && !$currentUser->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk mengedit ulasan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:1000',
            'city' => 'nullable|string|max:100',
        ]);

        $review->update([
            'rating' => (int) $validated['rating'],
            'comment' => $validated['comment'],
            'city' => !empty($validated['city']) ? trim($validated['city']) : null,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    /**
     * Hapus ulasan wisatawan (Hanya pemilik atau admin)
     */
    public function destroy(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk menghapus ulasan.');
        }

        $review = Review::findOrFail($id);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->id !== $review->user_id && !$currentUser->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk menghapus ulasan ini.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
