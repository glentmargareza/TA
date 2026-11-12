@extends('layouts.app')

@section('title', 'Syarat dan Ketentuan | Jeep Wukirsari Adventure Tour')

@section('content')
<div class="bg-slate-50 py-12 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8 text-center sm:text-left">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-900 mb-3">
                <span>&larr;</span> Kembali ke Beranda
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Syarat & Ketentuan Layanan</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Terakhir diperbarui: 11 November 2026 | Paguyuban Jeep Wisata Wukirsari</p>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs space-y-8 text-slate-700 text-sm leading-relaxed">
            
            <!-- 1. Ketentuan Umum -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">1</span>
                    Ketentuan Umum Pemesanan
                </h2>
                <p>
                    Dengan melakukan reservasi pada platform <strong>Jeep Wukirsari Adventure Tour</strong>, wisatawan dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan operasional yang berlaku di pangkalan wisata lereng Gunung Merapi.
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li>Pemesanan dapat dilakukan secara mandiri melalui website resmi dengan memilih paket, jadwal keberangkatan, dan metode pembayaran yang tersedia.</li>
                    <li>Wisatawan wajib mengisi data pemesan (Nama, Nomor WhatsApp aktif, dan Email) secara benar untuk keperluan penerbitan e-tiket dan komunikasi pangkalan.</li>
                    <li>Harga yang tertera pada katalog adalah mata uang <strong>Rupiah (IDR)</strong> dan berlaku per unit armada mobil jeep.</li>
                </ul>
            </section>

            <!-- 2. Batas Kapasitas & Keselamatan Offroad -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">2</span>
                    Kapasitas Armada & Standar Keselamatan Offroad
                </h2>
                <p>
                    Demi keselamatan dan kenyamanan selama menyusuri rute bebatuan lahar dan manuver air di lereng Merapi:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li>Kapasitas maksimal satu unit armada jeep adalah <strong>4 (empat) orang penumpang dewasa</strong>.</li>
                    <li>Rombongan dengan 5 orang hanya diizinkan dalam 1 armada apabila membawa minimal 1 orang anak-anak. Rombongan lebih dari 4 dewasa wajib menggunakan 2 unit jeep atau lebih sesuai kalkulator sistem.</li>
                    <li>Setiap armada didampingi oleh pengemudi lokal berlisensi dan berpengalaman. Seluruh peserta dilindungi asuransi perjalanan wisata pangkalan.</li>
                    <li>Peserta dilarang berdiri saat manuver ekstrem berlangsung dan wajib mematuhi arahan keselamatan dari pengemudi.</li>
                </ul>
            </section>

            <!-- 3. Ketentuan Pembayaran -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">3</span>
                    Skema dan Metode Pembayaran
                </h2>
                <p>
                    Kami menyediakan dua opsi skema pembayaran untuk kenyamanan wisatawan:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li><strong>Pembayaran Lunas (Full 100%)</strong>: Pembayaran penuh di muka melalui Payment Gateway resmi (Virtual Account, QRIS, E-Wallet) atau transfer bank.</li>
                    <li><strong>Uang Muka (DP 30%)</strong>: Wisatawan membayar deposit 30% dari total tagihan di awal untuk mengunci slot keberangkatan. Sisa tagihan 70% dilunasi secara tunai atau QRIS saat tiba di loket kasir basecamp sebelum naik jeep.</li>
                    <li>Batas waktu pembayaran untuk pesanan baru adalah <strong>30 menit</strong>. Jika tidak diselesaikan dalam 30 menit, sistem berhak membatalkan pesanan untuk membebaskan kuota slot kepada wisatawan lain.</li>
                </ul>
            </section>

            <!-- 4. Kebijakan Pembatalan & Pengembalian Dana (Refund) -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">4</span>
                    Kebijakan Pembatalan & Pengembalian Dana (Cancellation & Refund)
                </h2>
                <p>
                    Ketentuan pembatalan dan penyesuaian jadwal trip diatur sebagai berikut:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li><strong>Perubahan Jadwal (Reschedule)</strong>: Diizinkan gratis maksimal 1 (satu) kali dengan konfirmasi ke Customer Service paling lambat <strong>H-1</strong> sebelum jam keberangkatan, selama kuota sesi masih tersedia.</li>
                    <li><strong>Pembatalan oleh Wisatawan (H-1 atau lebih)</strong>: Pengembalian dana uang muka (DP) sebesar <strong>50%</strong> dari nominal yang telah disetorkan, atau dapat dikonversi menjadi voucher trip berlaku 3 bulan.</li>
                    <li><strong>Pembatalan Hari H (Kurang dari 24 Jam)</strong>: Uang muka (DP) yang telah dibayarkan dianggap hangus (*non-refundable*) karena armada dan driver telah dialokasikan secara khusus.</li>
                    <li><strong>Keadaan Kahar (Force Majeure)</strong>: Apabila trip dibatalkan karena erupsi Merapi, cuaca ekstrem, atau penutupan jalur oleh otoritas BPPTKG / Taman Nasional Gunung Merapi (TNGM), wisatawan berhak mendapatkan <strong>pengembalian dana penuh (100% Refund)</strong> atau jadwal ulang tanpa batas waktu.</li>
                </ul>
            </section>

            <!-- 5. Layanan Antar-Jemput (Pickup Shuttle) -->
            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">5</span>
                    Layanan Antar-Jemput (Pickup Zone)
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2 text-slate-600">
                    <li>Penjemputan area lokal dalam radius 5 km dari Basecamp Bedoyo Wukirsari disediakan secara <strong>Gratis (Rp 0)</strong>.</li>
                    <li>Untuk area di luar 5 km (Hotel Kaliurang, Kota Yogyakarta, Stasiun, Bandara YIA), berlaku tarif tambahan yang dihitung berdasarkan <strong>jumlah unit jeep</strong> yang diberangkatkan menjemput.</li>
                    <li>Wisatawan diharapkan bersiap di lobi penginapan 30 menit sebelum jadwal penjemputan.</li>
                </ul>
            </section>

            <!-- 6. Kontak Bantuan -->
            <section class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-950 space-y-2">
                <h3 class="font-bold text-sm">Butuh Bantuan Mengenai Syarat & Ketentuan?</h3>
                <p class="text-xs text-emerald-800">
                    Hubungi layanan pelanggan kami melalui WhatsApp resmi di <strong>+62 812-3456-7890</strong> atau kunjungi langsung Kantor Basecamp Paguyuban Jeep Wukirsari, Cangkringan, Sleman, D.I. Yogyakarta.
                </p>
            </section>

        </div>
    </div>
</div>
@endsection

