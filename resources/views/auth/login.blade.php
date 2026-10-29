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
                    Masuk ke Sistem
                </h1>
                <p class="text-xs text-merapiText-secondary">
                    Silakan pilih peran akun untuk melanjutkan
                </p>
            </div>

            <!-- Tab Pemilih Peran (User vs Admin) -->
            <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-2xl gap-1">
                <button type="button" id="tabUser" class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $activeRole === 'admin' ? 'text-merapiText-secondary hover:text-merapiText-primary' : 'bg-white text-wukirsari-primary shadow-xs' }}">
                    <span>🎒</span>
                    <span>Wisatawan</span>
                </button>
                <button type="button" id="tabAdmin" class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $activeRole === 'admin' ? 'bg-white text-wukirsari-primary shadow-xs' : 'text-merapiText-secondary hover:text-merapiText-primary' }}">
                    <span>🛡️</span>
                    <span>Admin Loket</span>
                </button>
            </div>

            <!-- Pesan Info Banner Sesuai Role -->
            <div id="roleInfoBanner" class="p-3 rounded-xl text-xs flex items-center gap-2.5 {{ $activeRole === 'admin' ? 'bg-wukirsari-sage text-wukirsari-primary border border-wukirsari-border' : 'bg-wukirsari-sage/40 text-wukirsari-primary border border-wukirsari-border' }}">
                <span id="roleInfoIcon">{{ $activeRole === 'admin' ? '🔒' : '👋' }}</span>
                <span id="roleInfoText">
                    {{ $activeRole === 'admin' ? 'Portal khusus staf loket, armada jeep & kasir basecamp.' : 'Masuk untuk mengelola e-tiket dan riwayat pesanan tour Anda.' }}
                </span>
            </div>

            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-start gap-2">
                <span class="text-sm">⚠️</span>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-merapiText-primary mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" id="password" required value="" placeholder="Masukkan kata sandi..." autocomplete="current-password" class="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-merapiText-secondary">
                        <input type="checkbox" name="remember" class="rounded text-wukirsari-primary focus:ring-0">
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-[11px] text-merapiText-muted">Sistem Wukirsari Merapi</span>
                </div>

                <button type="submit" id="btnSubmitLogin" class="w-full py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-bold shadow-md shadow-wukirsari-primary/20 transition-all flex items-center justify-center gap-2">
                    <span id="btnSubmitText">{{ $activeRole === 'admin' ? 'Masuk sebagai Admin Loket →' : 'Masuk sebagai Wisatawan →' }}</span>
                </button>
            </form>

            <!-- Footer Pendaftaran -->
            <div id="registerPrompt" class="text-center pt-2 {{ $activeRole === 'admin' ? 'hidden' : '' }}">
                <p class="text-xs text-merapiText-secondary">
                    Belum punya akun wisatawan? 
                    <a href="{{ route('register') }}" class="font-bold text-wukirsari-primary hover:underline">Daftar sekarang</a>
                </p>
            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabUser = document.getElementById('tabUser');
        const tabAdmin = document.getElementById('tabAdmin');
        const btnSubmitText = document.getElementById('btnSubmitText');
        const roleInfoBanner = document.getElementById('roleInfoBanner');
        const roleInfoIcon = document.getElementById('roleInfoIcon');
        const roleInfoText = document.getElementById('roleInfoText');
        const registerPrompt = document.getElementById('registerPrompt');

        function setRole(role) {
            if (role === 'admin') {
                tabAdmin.classList.add('bg-white', 'text-wukirsari-primary', 'shadow-xs');
                tabAdmin.classList.remove('text-merapiText-secondary');
                tabUser.classList.remove('bg-white', 'text-wukirsari-primary', 'shadow-xs');
                tabUser.classList.add('text-merapiText-secondary');

                btnSubmitText.innerText = 'Masuk sebagai Admin Loket →';
                
                roleInfoBanner.className = 'p-3 rounded-xl text-xs flex items-center gap-2.5 bg-wukirsari-sage text-wukirsari-primary border border-wukirsari-border';
                roleInfoIcon.innerText = '🔒';
                roleInfoText.innerText = 'Portal khusus staf loket, armada jeep & kasir basecamp.';
                registerPrompt.classList.add('hidden');
            } else {
                tabUser.classList.add('bg-white', 'text-wukirsari-primary', 'shadow-xs');
                tabUser.classList.remove('text-merapiText-secondary');
                tabAdmin.classList.remove('bg-white', 'text-wukirsari-primary', 'shadow-xs');
                tabAdmin.classList.add('text-merapiText-secondary');

                btnSubmitText.innerText = 'Masuk sebagai Wisatawan →';

                roleInfoBanner.className = 'p-3 rounded-xl text-xs flex items-center gap-2.5 bg-wukirsari-sage/40 text-wukirsari-primary border border-wukirsari-border';
                roleInfoIcon.innerText = '👋';
                roleInfoText.innerText = 'Masuk untuk mengelola e-tiket dan riwayat pesanan tour Anda.';
                registerPrompt.classList.remove('hidden');
            }
        }

        tabUser.addEventListener('click', () => setRole('user'));
        tabAdmin.addEventListener('click', () => setRole('admin'));
    });
</script>
@endsection
