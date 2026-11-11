@extends('layouts.app')

@section('title', 'Kebijakan Privasi | Jeep Wukirsari Adventure Tour')

@section('content')
<div class="bg-slate-50 py-12 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8 text-center sm:text-left">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-900 mb-3">
                <span>&larr;</span> Kembali ke Beranda
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kebijakan Privasi (Privacy Policy)</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Terakhir diperbarui: 11 November 2026 | Paguyuban Jeep Wisata Wukirsari</p>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs space-y-8 text-slate-700 text-sm leading-relaxed">
            
            <!-- 1. Pendahuluan -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">1</span>
                    Komitmen Privasi
                </h2>
                <p>
                    <strong>Jeep Wukirsari Adventure Tour</strong> berkomitmen untuk melindungi dan menghormati privasi data pribadi setiap pengguna dan wisatawan yang mengakses layanan platform kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda.
                </p>
            </section>

            <!-- 2. Data yang Dikumpulkan -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">2</span>
                    Informasi Pribadi yang Kami Kumpulkan
                </h2>
                <p>
                    Saat Anda melakukan reservasi atau membuat akun, informasi yang kami kumpulkan meliputi:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li><strong>Identitas Pemesan</strong>: Nama lengkap, alamat email, dan nomor WhatsApp aktif.</li>
                    <li><strong>Detail Reservasi</strong>: Tanggal trip, pilihan sesi jam, jumlah rombongan, dan paket tur yang dipilih.</li>
                    <li><strong>Data Penjemputan</strong>: Alamat hotel, vila, titik kumpul, dan koordinat geolokasi penjemputan shuttle.</li>
                    <li><strong>Data Transaksi</strong>: Nomor referensi pesanan, status pembayaran, dan metode pembayaran yang digunakan (kami <em>tidak pernah</em> menyimpan nomor kartu kredit atau PIN perbankan Anda).</li>
                </ul>
            </section>

            <!-- 3. Penggunaan Data -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">3</span>
                    Tujuan Penggunaan Informasi
                </h2>
                <p>
                    Data pribadi yang dikumpulkan digunakan semata-mata untuk keperluan operasional wisata:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li>Menerbitkan dan mengirimkan bukti e-tiket resmi beserta QR Code check-in loket.</li>
                    <li>Menghubungi pemesan melalui WhatsApp mengenai koordinasi jadwal dan penjemputan armada.</li>
                    <li>Memproses verifikasi transaksi keuangan melalui gerbang pembayaran resmi berlisensi Bank Indonesia (Midtrans).</li>
                    <li>Mendaftarkan polis asuransi keselamatan perjalanan wisata offroad bagi setiap peserta rombongan.</li>
                </ul>
            </section>

            <!-- 4. Keamanan Pembayaran -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">4</span>
                    Keamanan Transaksi & Payment Gateway
                </h2>
                <p>
                    Seluruh transaksi digital pada platform kami diproses melalui koneksi aman terenkripsi (HTTPS / SSL 256-bit). Pemrosesan pembayaran elektronik dikelola oleh <strong>PT Midtrans</strong>, penyedia payment gateway yang tersertifikasi PCI-DSS Level 1 dan diawasi oleh Bank Indonesia. Kami tidak pernah melihat atau menyimpan data rahasia kartu pembayaran Anda pada server kami.
                </p>
            </section>

            <!-- 5. Pembagian Data ke Pihak Ketiga -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">5</span>
                    Kerahasiaan & Pembagian Data
                </h2>
                <p>
                    Kami <strong>tidak akan pernah menjual, menyewakan, atau menyebarluaskan</strong> data pribadi wisatawan kepada pihak ketiga untuk kepentingan komersial atau periklanan. Data hanya dibagikan kepada pengemudi armada bertugas demi kelancaran penjemputan atau apabila diwajibkan oleh ketentuan hukum Republik Indonesia.
                </p>
            </section>

            <!-- 6. Hak Pengguna & Kontak DPO -->
            <section class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-950 space-y-2">
                <h3 class="font-bold text-sm">Hak Akses & Pembaruan Data</h3>
                <p class="text-xs text-emerald-800">
                    Wisatawan berhak memperbarui data kontak atau meminta penghapusan riwayat akun dengan menghubungi tim kami melalui email resmi <strong>kontak@jeepwukirsari.id</strong> atau WhatsApp <strong>+62 812-3456-7890</strong>.
                </p>
            </section>

        </div>
    </div>
</div>
@endsection

