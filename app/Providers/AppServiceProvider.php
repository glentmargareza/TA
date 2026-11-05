<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa IPv4 untuk semua panggilan HTTP keluar (IPv6 macet di jaringan ini)
        Http::globalOptions(['force_ip_resolve' => 'v4']);
    }
}