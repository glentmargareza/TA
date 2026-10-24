<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('price_per_jeep', 'asc')->get();
        return view('home', compact('packages'));
    }

    public function showPackage($slug)
    {
        $package = Package::where('slug', $slug)->firstOrFail();
        return view('packages.show', compact('package'));
    }
}
