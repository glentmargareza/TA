@extends('layouts.app')

@section('title', 'Masuk ke Akun | Jeep Wukirsari Adventure')

@section('content')
<div class="py-12 sm:py-16 bg-wukirsari-cream min-h-[calc(100vh-80px)] flex items-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">

        <!-- Card Container -->
        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6">
            
            <!-- Header Brand -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-merapiText-primary font-display">
                    Masuk ke Akun
                </h1>
                <p class="text-xs text-merapiText-secondary">
                    Masukkan email dan kata sandi Anda untuk melanjutkan
                </p>
            </div>

            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-start gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-merapiText-muted hover:text-wukirsari-primary transition-colors cursor-pointer" title="Lihat / Sembunyikan Kata Sandi" tabindex="-1">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-slash w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
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

                <button type="submit" id="btnSubmitLogin" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <span>Masuk Sekarang →</span>
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
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeSlash = btn.querySelector('.eye-slash');
        if (eyeOpen && eyeSlash) {
            eyeOpen.classList.toggle('hidden', isPass);
            eyeSlash.classList.toggle('hidden', !isPass);
        }
    }
</script>
@endsection
