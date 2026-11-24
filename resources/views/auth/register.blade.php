@extends('layouts.app')

@section('title', 'Daftar Akun Wisatawan | Jeep Wukirsari Adventure')

@section('content')
<div class="py-12 sm:py-16 bg-wukirsari-cream min-h-[calc(100vh-80px)] flex items-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">

        <div class="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center mx-auto shadow-xs font-bold font-display text-sm tracking-wider">
                    JW
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
                <p>&bull; {{ $error }}</p>
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
                    <div class="relative">
                        <input type="password" name="password" id="registerPassword" required placeholder="Minimal 6 karakter" class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                        <button type="button" onclick="togglePasswordVisibility('registerPassword', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-merapiText-muted hover:text-wukirsari-primary transition-colors cursor-pointer" title="Lihat / Sembunyikan Kata Sandi" aria-label="Lihat atau sembunyikan kata sandi" tabindex="-1">
                            <span class="icon-eye flex items-center">
                                <x-icon name="eye" class="w-4 h-4" />
                            </span>
                            <span class="icon-eye-slash hidden flex items-center">
                                <x-icon name="eye-slash" class="w-4 h-4 text-wukirsari-primary" />
                            </span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Konfirmasi Kata Sandi *</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="registerPasswordConfirmation" required placeholder="Ulangi kata sandi" class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                        <button type="button" onclick="togglePasswordVisibility('registerPasswordConfirmation', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-merapiText-muted hover:text-wukirsari-primary transition-colors cursor-pointer" title="Lihat / Sembunyikan Kata Sandi" aria-label="Lihat atau sembunyikan kata sandi" tabindex="-1">
                            <span class="icon-eye flex items-center">
                                <x-icon name="eye" class="w-4 h-4" />
                            </span>
                            <span class="icon-eye-slash hidden flex items-center">
                                <x-icon name="eye-slash" class="w-4 h-4 text-wukirsari-primary" />
                            </span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center">
                    <span>Daftar Akun Sekarang</span>
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
