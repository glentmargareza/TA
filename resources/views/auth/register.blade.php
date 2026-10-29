@extends('layouts.app')

@section('title', 'Daftar Akun Wisatawan | Jeep Wukirsari Adventure')

@section('content')
<div class="py-12 sm:py-16 bg-wukirsari-cream min-h-[calc(100vh-80px)] flex items-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">

        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-merapiText-primary font-display">
                    Daftar Akun Wisatawan
                </h1>
                <p class="text-xs text-merapiText-secondary">
                    Nikmati kemudahan reservasi dan simpan e-tiket tour Anda
                </p>
            </div>

            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Rian Pratama" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Alamat Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Nomor WhatsApp Aktif *</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Kata Sandi *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Konfirmasi Kata Sandi *</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center gap-2">
                    <span>Daftar Akun Sekarang →</span>
                </button>
            </form>

            <div class="text-center pt-2">
                <p class="text-xs text-merapiText-secondary">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-wukirsari-primary hover:underline">Masuk di sini</a>
                </p>
            </div>

        </div>

    </div>
</div>
@endsection
