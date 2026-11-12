<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('price_per_jeep', 'asc')->get();
        $reviews = Review::with(['user', 'package'])
            ->where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();
        $totalReviewsCount = Review::where('is_approved', true)->count();
        $avgRating = Review::where('is_approved', true)->avg('rating') ?: 5.0;

        $settingsFile = storage_path('app/basecamp_settings.json');
        $homeSettings = [
            'name' => 'Jeep Wukirsari',
            'cs_phone' => '+62 812-3456-7890',
            'basecamp_address' => 'Jl. Bebeng - Cangkringan, Wukirsari, Sleman, D.I. Yogyakarta',
            'hero_badge' => 'Offroad Lereng Merapi',
            'hero_headline' => 'Jelajahi Keindahan Alam Merapi Bersama Jeep Wukirsari',
            'hero_subtitle' => 'Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.',
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
        ];

        if (file_exists($settingsFile)) {
            $saved = json_decode(file_get_contents($settingsFile), true);
            if (is_array($saved)) {
                $homeSettings = array_merge($homeSettings, $saved);
            }
        }

        return view('home', compact('packages', 'reviews', 'totalReviewsCount', 'avgRating', 'homeSettings'));
    }

    public function packagesIndex(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('search', '');

        $query = Package::where('is_active', true);

        if ($category && $category !== 'all') {
            if ($category === 'short') {
                $query->where('slug', 'short-trip');
            } elseif ($category === 'medium') {
                $query->whereIn('slug', ['medium-trip-a', 'medium-trip-b']);
            } elseif ($category === 'long') {
                $query->where('slug', 'long-trip');
            } elseif ($category === 'sunrise') {
                $query->where('slug', 'sunrise-trip');
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $packages = $query->orderBy('price_per_jeep', 'asc')->get();
        $allCount = Package::where('is_active', true)->count();

        return view('packages.index', compact('packages', 'allCount', 'category', 'search'));
    }

    public function showPackage(string $slug)
    {
        $package = Package::where('slug', $slug)->firstOrFail();
        $otherPackages = Package::where('is_active', true)->where('id', '!=', $package->id)->take(3)->get();
        
        $reviews = Review::with('user')
            ->where('package_id', $package->id)
            ->where('is_approved', true)
            ->latest()
            ->get();
        $packageAvgRating = $reviews->count() > 0 ? $reviews->avg('rating') : 5.0;

        return view('packages.show', compact('package', 'otherPackages', 'reviews', 'packageAvgRating'));
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function privacy()
    {
        return view('legal.privacy');
    }
}
