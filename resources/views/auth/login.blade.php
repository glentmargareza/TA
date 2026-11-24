@extends('layouts.app')

@section('title', 'Masuk ke Akun | Jeep Wukirsari Adventure')

@section('content')
<div class="py-12 sm:py-16 bg-wukirsari-cream min-h-[calc(100vh-80px)] flex items-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">

        <!-- Card Container -->
        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6">
            
            <!-- Header Brand -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center mx-auto shadow-xs font-bold font-display text-sm tracking-wider">
                    JW
                </div>
                <h1 class="text-2xl font-extrabold text-merapiText-primary font-display">
                    Masuk ke Akun
                </h1>
                <p class="text-xs text-merapiText-secondary">
                    Masukkan email dan kata sandi Anda untuk melanjutkan
                </p>
            </div>

            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs">
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <!-- Form Login Tunggal -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required value="" placeholder="Masukkan kata sandi..." autocomplete="current-password" class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-merapiText-muted hover:text-wukirsari-primary transition-colors cursor-pointer" title="Lihat / Sembunyikan Kata Sandi" aria-label="Lihat atau sembunyikan kata sandi" tabindex="-1">
                            <span class="icon-eye flex items-center">
                                <x-icon name="eye" class="w-4 h-4" />
                            </span>
                            <span class="icon-eye-slash hidden flex items-center">
                                <x-icon name="eye-slash" class="w-4 h-4 text-wukirsari-primary" />
                            </span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-merapiText-secondary">
                        <input type="checkbox" name="remember" class="rounded text-wukirsari-primary focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-[11px] text-merapiText-muted">Sistem Wukirsari Merapi</span>
                </div>

                <button type="submit" id="btnSubmitLogin" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center cursor-pointer active:scale-95">
                    <span>Masuk Sekarang</span>
                </button>
            </form>

            <!-- Footer Pendaftaran -->
            <div class="text-center pt-2 border-t border-wukirsari-border">
                <p class="text-xs text-merapiText-secondary">
                    Belum punya akun wisatawan? 
                    <a href="{{ route('register') }}" class="font-bold text-wukirsari-primary hover:underline">Daftar sekarang</a>
                </p>
            </div>

        </div>

    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        
        const eyeIcon = btn.querySelector('.icon-eye');
        const eyeSlashIcon = btn.querySelector('.icon-eye-slash');
        if (eyeIcon && eyeSlashIcon) {
            eyeIcon.classList.toggle('hidden', isPass);
            eyeSlashIcon.classList.toggle('hidden', !isPass);
        }
        
        btn.setAttribute('title', isPass ? 'Sembunyikan Kata Sandi' : 'Lihat Kata Sandi');
    }
</script>
@endsection
