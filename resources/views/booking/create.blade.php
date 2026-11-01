<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pemesanan Jeep Wukirsari</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --green-900:#14301b; --green-800:#1d4026; --green-700:#255231; --green-600:#2f6a3f;
  --green-100:#e3efe5; --green-50:#f1f6f2;
  --cream:#f5f4ee; --card:#ffffff; --ink:#15221a; --muted:#6b7a70; --line:#e4e8e2;
  --accent:#e8a33d; --danger:#c0392b;
  --radius:20px; --shadow:0 10px 30px -12px rgba(20,48,27,.18);
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--cream);color:var(--ink);line-height:1.5}
button,input,select,textarea{font:inherit;color:inherit}
button{cursor:pointer;border:0;background:none}

/* NAV */
.nav{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.nav-in{max-width:1200px;margin:auto;padding:12px 24px;display:flex;align-items:center;gap:24px}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit}
.brand-logo{width:44px;height:44px;border-radius:12px;background:var(--green-800);display:grid;place-items:center;font-size:22px}
.brand b{display:block;font-weight:800;letter-spacing:.2px;line-height:1.1}
.brand small{font-size:11px;letter-spacing:1.5px;color:var(--muted);font-weight:600}
.menu{display:flex;gap:26px;margin:0 auto}
.menu a{text-decoration:none;color:#3b4a40;font-weight:500;font-size:14px}
.menu a:hover{color:var(--green-700)}
.nav-right{display:flex;align-items:center;gap:12px}
.icbtn{width:40px;height:40px;display:grid;place-items:center;color:#3b4a40;border-radius:50%}
.icbtn:hover{background:var(--green-50)}
.login{border:1px solid var(--line);padding:9px 18px;border-radius:999px;font-size:13.5px;font-weight:600;text-decoration:none;color:var(--ink);background:#fff}
.nav-cta{background:var(--green-800);color:#fff;padding:11px 20px;border-radius:999px;font-weight:700;font-size:14px;text-decoration:none}

.page-head{max-width:1200px;margin:auto;padding:40px 24px 26px}
.page-head small{font-size:12px;font-weight:800;letter-spacing:1.5px;color:var(--green-800)}
.page-head h1{font-size:clamp(28px,4vw,38px);font-weight:800;letter-spacing:-.5px;margin:6px 0 6px}
.page-head p{color:var(--muted)}

/* LAYOUT */
.wrap{max-width:1200px;margin:0 auto 60px;padding:0 24px;display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:28px;align-items:start}
.stepper{grid-column:1/-1;display:flex;gap:10px;background:var(--card);border-radius:999px;padding:8px;box-shadow:var(--shadow);overflow-x:auto}
.st{flex:1;display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:999px;font-size:13px;font-weight:700;color:var(--muted);white-space:nowrap;transition:.25s}
.st i{font-style:normal;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;background:var(--green-100);color:var(--green-700);font-size:12px}
.st.on{background:var(--green-800);color:#fff}
.st.on i{background:var(--accent);color:var(--green-900)}
.st.done i{background:var(--green-600);color:#fff}

.card{background:var(--card);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);margin-bottom:22px;border:1px solid rgba(0,0,0,.02)}
.card h2{display:flex;align-items:center;gap:12px;font-size:19px;font-weight:800;margin-bottom:6px}
.num{width:30px;height:30px;border-radius:50%;background:var(--green-800);color:#fff;display:grid;place-items:center;font-size:14px}
.sub{color:var(--muted);font-size:14px;margin:0 0 20px 42px}

/* PAKET (tanpa ikon & spot) */
.pk{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:14px}
.pkg{position:relative;text-align:left;padding:18px 18px 16px;border:1.5px solid var(--line);border-radius:16px;background:#fff;transition:.2s;display:flex;flex-direction:column;gap:6px}
.pkg:hover{border-color:var(--green-600);transform:translateY(-3px);box-shadow:0 12px 24px -14px rgba(20,48,27,.4)}
.pkg.sel{border-color:var(--green-800);background:var(--green-50);box-shadow:0 0 0 3px rgba(29,64,38,.12)}
.pkg h3{font-size:15px;font-weight:800;padding-right:26px}
.pkg .pr{font-size:19px;font-weight:800;color:var(--green-700)}
.pkg .meta{font-size:12.5px;color:var(--muted)}
.check{position:absolute;top:14px;right:14px;width:22px;height:22px;border-radius:50%;background:var(--green-800);color:#fff;font-size:12px;display:none;place-items:center}
.pkg.sel .check{display:grid}

/* FORM */
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.f{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}
.f label{font-size:13px;font-weight:700;color:#2e3d33}
.in{width:100%;padding:13px 15px;border:1.5px solid var(--line);border-radius:12px;background:#fff;outline:none;transition:.2s;font-size:14.5px}
.in:focus{border-color:var(--green-600);box-shadow:0 0 0 4px rgba(47,106,63,.12)}
.in.err{border-color:var(--danger)}
select.in{appearance:none;-webkit-appearance:none;padding-right:42px;cursor:pointer;
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%231d4026' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 16px center}
.hint{font-size:12.5px;color:var(--muted)}
.note{display:flex;gap:10px;background:#fff7e8;border:1px solid #f3dcae;color:#7a5410;padding:12px 14px;border-radius:12px;font-size:13px;margin-top:6px}
.qty{display:inline-flex;align-items:center;border:1.5px solid var(--line);border-radius:12px;overflow:hidden;align-self:flex-start}
.qty button{width:44px;height:44px;font-size:20px;font-weight:700;color:var(--green-700)}
.qty button:hover{background:var(--green-50)}
.qty output{min-width:52px;text-align:center;font-weight:800}

.arrive{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px}
.ar{padding:14px;border:1.5px solid var(--line);border-radius:14px;font-weight:700;font-size:13.5px;background:#fff;transition:.2s}
.ar:hover{border-color:var(--green-600)}
.ar.sel{border-color:var(--green-800);background:var(--green-50);color:var(--green-800);box-shadow:0 0 0 3px rgba(29,64,38,.1)}

/* PENJEMPUTAN OTOMATIS */
.search{display:flex;gap:10px}
.search .in{flex:1}
.btn-s{padding:0 18px;border-radius:12px;background:var(--green-800);color:#fff;font-weight:700;font-size:14px;white-space:nowrap}
.btn-s:hover{background:var(--green-700)}
.btn-o{padding:10px 14px;border:1.5px solid var(--line);border-radius:12px;font-weight:700;font-size:13px;color:var(--green-800);background:#fff}
.btn-o:hover{border-color:var(--green-600);background:var(--green-50)}
.results{border:1.5px solid var(--line);border-radius:12px;background:#fff;overflow:hidden;max-height:220px;overflow-y:auto;box-shadow:var(--shadow)}
.res{display:block;width:100%;text-align:left;padding:11px 14px;font-size:13px;border-bottom:1px solid var(--line);color:var(--ink)}
.res:last-child{border-bottom:0}
.res:hover{background:var(--green-50)}
#map{height:270px;border-radius:14px;border:1.5px solid var(--line);margin:12px 0;z-index:1}
.dist{display:flex;align-items:center;justify-content:space-between;gap:14px;border-radius:14px;padding:14px 16px;background:var(--green-50);border:1.5px solid #cfe0d2;margin-bottom:16px}
.dist.over{background:#fff7e8;border-color:#f3dcae}
.dist .k{font-size:22px;font-weight:800;color:var(--green-800)}
.dist.over .k{color:#9a6a0c}
.dist small{display:block;color:var(--muted);font-size:12.5px}
.dist .tag{font-size:12px;font-weight:800;padding:6px 12px;border-radius:999px;background:var(--green-800);color:#fff;white-space:nowrap}
.dist.over .tag{background:var(--accent);color:var(--green-900)}

/* SUMMARY */
.sum{position:sticky;top:90px}
.sum .card{margin:0;padding:26px}
.sum h3{font-size:18px;font-weight:800;padding-bottom:14px;border-bottom:1px solid var(--line);margin-bottom:14px}
.ticket{background:var(--green-50);border:1.5px dashed #b8cfbc;border-radius:14px;padding:14px;margin-bottom:14px;font-size:13px}
.ticket b{display:block;font-size:15px;color:var(--green-800)}
.ticket span{color:var(--muted)}
.row{display:flex;justify-content:space-between;gap:10px;padding:7px 0;font-size:14px;color:#52625a}
.row b{color:var(--ink);font-weight:700;text-align:right}
.row .free,.row b.free{color:#0f8a52;font-weight:700}
.row.extra{background:#fff7e8;border:1px solid #f3dcae;border-radius:10px;padding:9px 12px;margin:4px 0;color:#7a5410}
.row.extra b{color:#9a6a0c}
.row.extra small{display:block;font-size:11.5px;opacity:.85}
.total{display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--line);margin-top:8px;padding-top:16px}
.total span{font-weight:800}
.total strong{font-size:27px;font-weight:800;color:var(--green-800);letter-spacing:-.5px}
.pay{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:18px 0 12px}
.po{text-align:left;padding:12px;border:1.5px solid var(--line);border-radius:14px;transition:.2s}
.po b{display:block;font-size:13px}
.po small{font-size:11.5px;color:var(--muted)}
.po.sel{border-color:var(--green-800);background:var(--green-50);box-shadow:0 0 0 3px rgba(29,64,38,.1)}
.due{background:var(--green-900);color:#fff;border-radius:14px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.due small{display:block;color:#a9c4af;font-size:12px}
.due strong{font-size:20px}
.cta{width:100%;background:linear-gradient(135deg,var(--green-700),var(--green-900));color:#fff;padding:16px;border-radius:14px;font-weight:800;font-size:15.5px;display:flex;justify-content:center;align-items:center;gap:10px;transition:.2s;box-shadow:0 12px 22px -12px rgba(20,48,27,.7)}
.cta:hover{transform:translateY(-2px)}
.secure{display:flex;justify-content:center;gap:14px;flex-wrap:wrap;margin-top:14px;font-size:12px;color:var(--muted);font-weight:600}

.toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,120px);background:var(--green-900);color:#fff;padding:13px 20px;border-radius:12px;font-size:14px;font-weight:600;transition:.35s;z-index:1000;max-width:90%}
.toast.show{transform:translate(-50%,0)}

.mobile-bar{display:none}
@media(max-width:980px){
  .menu,.login,.icbtn{display:none}
  .nav-in{justify-content:space-between}
  .wrap{grid-template-columns:1fr}
  .sum{position:static}
  .mobile-bar{display:flex;position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid var(--line);padding:12px 18px;justify-content:space-between;align-items:center;z-index:60;box-shadow:0 -8px 24px -12px rgba(0,0,0,.2)}
  .mobile-bar small{display:block;color:var(--muted);font-size:11.5px}
  .mobile-bar strong{font-size:18px;color:var(--green-800)}
  .mobile-bar a{background:var(--green-800);color:#fff;padding:12px 20px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none}
  body{padding-bottom:78px}
}
@media(max-width:560px){
  .grid2,.arrive{grid-template-columns:1fr}
  .card{padding:20px}
  .sub{margin-left:0}
  .nav-cta{display:none}
  .search{flex-direction:column}
  .btn-s{padding:12px}
}
</style>
</head>
<body>

<header class="nav">
  <div class="nav-in">
    <a class="brand" href="#">
      <div class="brand-logo">🚙</div>
      <div><b>JEEP WUKIRSARI</b><small>ADVENTURE TOUR</small></div>
    </a>
    <nav class="menu">
      <a href="#">Beranda</a><a href="#">Tentang Kami</a><a href="#">Galeri</a><a href="#">Ulasan</a><a href="#">Cek Tiket</a>
    </nav>
    <div class="nav-right">
      <button class="icbtn" aria-label="Cari"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg></button>
      <button class="icbtn" aria-label="Telepon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg></button>
      <a class="login" href="#">Masuk</a>
      <a class="nav-cta" href="#form">Booking Sekarang →</a>
    </div>
  </div>
</header>

<div class="page-head">
  <small>FORMULIR RESERVASI</small>
  <h1>Pemesanan Jeep Wukirsari</h1>
  <p>Pilih paket, jadwal keberangkatan, dan opsi pembayaran aman (Full atau Uang Muka DP 30%).</p>
</div>

<main class="wrap" id="form">
  <div class="stepper" id="stepper">
    <div class="st on" data-s="1"><i>1</i>Paket</div>
    <div class="st" data-s="2"><i>2</i>Jadwal</div>
    <div class="st" data-s="3"><i>3</i>Kapasitas</div>
    <div class="st" data-s="4"><i>4</i>Kontak</div>
  </div>

  <div>
    <!-- 1 PAKET -->
    <section class="card" data-sec="1">
      <h2><span class="num">1</span>Pilih Paket Wisata</h2>
      <p class="sub">Harga per unit jeep.</p>
      <div class="pk" id="pkgs"></div>
    </section>

    <!-- 2 JADWAL -->
    <section class="card" data-sec="2">
      <h2><span class="num">2</span>Pilih Tanggal &amp; Jam Trip</h2>
      <p class="sub">Pilih hari dan sesi keberangkatan.</p>
      <div class="grid2">
        <div class="f">
          <label for="date">Tanggal Kunjungan</label>
          <input id="date" class="in" type="date">
        </div>
        <div class="f">
          <label for="session">Sesi Waktu Keberangkatan</label>
          <select id="session" class="in"></select>
          <span class="hint" id="sessHint"></span>
        </div>
      </div>
    </section>

    <!-- 3 KAPASITAS -->
    <section class="card" data-sec="3">
      <h2><span class="num">3</span>Kapasitas &amp; Jumlah Jeep</h2>
      <p class="sub">Satu jeep dapat memuat maksimal 4 penumpang.</p>
      <div class="grid2">
        <div class="f">
          <label>Jumlah Penumpang (Orang)</label>
          <div class="qty"><button type="button" id="paxMinus">−</button><output id="pax">4</output><button type="button" id="paxPlus">+</button></div>
          <span class="hint">*Maksimal 4 penumpang per armada jeep.</span>
        </div>
        <div class="f">
          <label>Jumlah Armada Jeep</label>
          <div class="qty"><button type="button" id="minus">−</button><output id="qty">1</output><button type="button" id="plus">+</button></div>
          <span class="hint" id="jeepHint" style="color:var(--green-700);font-weight:600"></span>
        </div>
      </div>
    </section>

    <!-- 4 KEDATANGAN & KONTAK -->
    <section class="card" data-sec="4">
      <h2><span class="num">4</span>Opsi Kedatangan &amp; Kontak</h2>
      <p class="sub">Pilih cara Anda tiba, lalu isi kontak untuk pengiriman tiket.</p>
      <div class="arrive">
        <button type="button" class="ar sel" data-m="self">Datang Sendiri ke Basecamp</button>
        <button type="button" class="ar" data-m="shuttle">Layanan Antar-Jemput (Shuttle)</button>
      </div>

      <div id="pickupBox" hidden>
        <div class="f">
          <label for="addr">Nama Hotel / Alamat Penjemputan *</label>
          <input id="addr" class="in" placeholder="Contoh: Hotel Tentrem, Jl. P. Mangkubumi, Yogyakarta">
          <span class="hint">Informasi untuk driver saat menjemput.</span>
        </div>
        <div class="f" style="margin-bottom:12px">
          <label>Titik Lokasi Penjemputan *</label>
          <button type="button" class="btn-s" id="btnGps" style="padding:14px">Bagikan Lokasi Saya Sekarang</button>
          <span class="hint">Tekan saat Anda berada di lokasi jemput. Jarak dari basecamp dihitung otomatis.</span>
        </div>
        <div class="f">
          <label for="link">Atau tempel link Google Maps / koordinat</label>
          <div class="search">
            <input id="link" class="in" placeholder="https://www.google.com/maps/... atau -7.7956, 110.3695">
            <button type="button" class="btn-s" id="btnLink">Hitung</button>
          </div>
          <span class="hint">Di Google Maps: tahan lama pada lokasi hotel, lalu salin angka koordinat yang muncul di bagian atas.</span>
        </div>
        <div class="dist" id="dist">
          <div><div class="k" id="distKm">—</div><small id="distTxt">Bagikan lokasi untuk menghitung jarak otomatis.</small></div>
          <span class="tag" id="distTag">Belum dihitung</span>
        </div>
        <button type="button" class="btn-o" id="btnManual" style="margin-bottom:18px">Lokasi tidak terdeteksi? Biaya dikonfirmasi admin via WhatsApp</button>
      </div>

      <div class="grid2">
        <div class="f"><label for="name">Nama Lengkap Pemesan *</label><input id="name" class="in" placeholder="Contoh: Budi Santoso" autocomplete="name"></div>
        <div class="f"><label for="wa">Nomor WhatsApp Aktif *</label><input id="wa" class="in" inputmode="tel" placeholder="Contoh: 081234567890" autocomplete="tel">
          <span class="hint">*Tiket QR Code dikirim ke nomor ini.</span></div>
      </div>
    </section>
  </div>

  <!-- RINGKASAN -->
  <aside class="sum" id="pay">
    <div class="card">
      <h3>Ringkasan Tagihan</h3>
      <div class="ticket"><b id="tPkg">Short Trip</b><span id="tDate">Belum pilih tanggal</span></div>
      <div class="row"><span id="lJeep">Sewa Jeep</span><b id="vJeep">Rp 450.000</b></div>
      <div class="row"><span>Layanan Penjemputan</span><b id="vPick">Tidak dipilih</b></div>
      <div class="row extra" id="rowExtra" style="display:none"><span id="lExtra">Biaya Tambahan Jemput</span><b id="vExtra">Rp 0</b></div>
      <div class="row"><span>Asuransi &amp; BBM</span><b class="free">Gratis (Termasuk)</b></div>
      <div class="total"><span>Total Biaya</span><strong id="vTotal">Rp 450.000</strong></div>

      <div style="margin-top:20px;font-size:13px;font-weight:800">Pilih Skema Pembayaran</div>
      <div class="pay">
        <button type="button" class="po sel" data-p="full"><b>Bayar Lunas (100%)</b><small>Langsung beres</small></button>
        <button type="button" class="po" data-p="dp"><b>Uang Muka (DP 30%)</b><small>Pelunasan di basecamp</small></button>
      </div>
      <div class="due"><div><small id="dueLbl">Wajib Dibayar Sekarang</small><strong id="vDue">Rp 450.000</strong></div><span id="dueSub" style="font-size:12px;color:#a9c4af"></span></div>
      <button class="cta" id="submit" type="button">Konfirmasi &amp; Terbitkan E-Tiket <span>→</span></button>
      <div class="secure"><span>Data aman</span><span>Reschedule gratis H-2</span><span>Tiket resmi</span></div>
    </div>
  </aside>
</main>

<!-- FOOTER: tempel footer asli website Anda di sini agar bagian bawah tetap sama -->

<div class="mobile-bar"><div><small>Total biaya</small><strong id="mTotal">Rp 450.000</strong></div><a href="#pay">Lihat Ringkasan</a></div>
<div class="toast" id="toast"></div>

<script>
/* ====== PENGATURAN ====== */
const WA_NUMBER   = "6281234567890";  // GANTI: nomor WhatsApp admin (format 62...)
const BASECAMP    = {lat:-7.6352, lng:110.4366}; // GANTI: koordinat basecamp (klik kanan di Google Maps > salin koordinat)
const FREE_KM     = 3;      // jarak jemput gratis (km)
const EXTRA_PER_KM = 5000;  // biaya tambahan per km di atas 3 km (sesuaikan)
const PER_JEEP    = 4;      // maks. penumpang per jeep

const PACKAGES = [
  {id:'short', name:'Short Trip',      price:450000, dur:'1.5 - 2 jam'},
  {id:'medA',  name:'Medium Trip (A)', price:550000, dur:'2 - 2.5 jam'},
  {id:'medB',  name:'Medium Trip (B)', price:550000, dur:'2 - 2.5 jam'},
  {id:'long',  name:'Long Trip',       price:650000, dur:'2 - 3.5 jam'},
  {id:'sun',   name:'Sunrise Trip',    price:650000, dur:'start 04.00 WIB', sunrise:true},
];
const SESSIONS = [
  {v:'04:30', label:'Sunrise Tour (04:30 WIB)',       sunrise:true},
  {v:'08:00', label:'Sesi Pagi 1 (08:00 WIB)'},
  {v:'10:30', label:'Sesi Pagi 2 (10:30 WIB)'},
  {v:'13:30', label:'Sesi Siang / Sore (13:30 WIB)'},
];

const $ = s => document.querySelector(s);
const rp = n => 'Rp ' + n.toLocaleString('id-ID');
const state = {pkg:'short', qty:1, pax:4, session:null, pickup:false, pay:'full', km:null, lat:null, lng:null, calc:false, manual:false};

/* ====== PAKET ====== */
const pkgsEl = $('#pkgs');
pkgsEl.innerHTML = PACKAGES.map(p => `
  <button type="button" class="pkg ${p.id===state.pkg?'sel':''}" data-id="${p.id}">
    <h3>${p.name}</h3>
    <div class="pr">${rp(p.price)}</div>
    <div class="meta">${p.dur}</div>
    <span class="check">✓</span>
  </button>`).join('');
pkgsEl.addEventListener('click', e=>{
  const b = e.target.closest('.pkg'); if(!b) return;
  state.pkg = b.dataset.id;
  pkgsEl.querySelectorAll('.pkg').forEach(x=>x.classList.toggle('sel', x===b));
  renderSessions(); update(); setStep(2);
});

/* ====== TANGGAL & SESI ====== */
const dateEl = $('#date');
const iso = d => new Date(d.getTime()-d.getTimezoneOffset()*60000).toISOString().slice(0,10);
dateEl.min = iso(new Date());
const def = new Date(); def.setDate(def.getDate()+1);
dateEl.value = iso(def);
dateEl.addEventListener('change', ()=>{renderSessions(); update(); setStep(2)});

function renderSessions(){
  const p = PACKAGES.find(x=>x.id===state.pkg);
  const sel = $('#session');
  const isToday = dateEl.value === iso(new Date());
  const now = new Date(); const nowMin = now.getHours()*60+now.getMinutes();
  const prev = state.session;
  sel.innerHTML = SESSIONS.map(s=>{
    const [h,m] = s.v.split(':').map(Number);
    const passed = isToday && (h*60+m) <= nowMin;
    const allowed = p.sunrise ? s.sunrise : !s.sunrise;
    return `<option value="${s.v}" ${(!allowed||passed)?'disabled':''}>${s.label}</option>`;
  }).join('');
  const firstOk = [...sel.options].find(o=>!o.disabled);
  const keep = [...sel.options].find(o=>o.value===prev && !o.disabled);
  sel.value = keep ? keep.value : (firstOk ? firstOk.value : '');
  state.session = sel.value || null;
  $('#sessHint').textContent = p.sunrise
    ? 'Sunrise Trip hanya tersedia di sesi Sunrise Tour.'
    : (firstOk ? '' : 'Semua sesi hari ini sudah lewat, pilih tanggal lain.');
}
$('#session').addEventListener('change', e=>{ state.session = e.target.value; update(); setStep(3); });

/* ====== KAPASITAS ====== */
$('#paxPlus').onclick = ()=>{
  if(state.pax<40){ state.pax++; state.qty = Math.max(state.qty, Math.ceil(state.pax/PER_JEEP)); update(); setStep(3); }
};
$('#paxMinus').onclick = ()=>{ if(state.pax>1){ state.pax--; update(); setStep(3); } };
$('#plus').onclick = ()=>{ if(state.qty<10){ state.qty++; update(); setStep(3); } };
$('#minus').onclick = ()=>{
  const minJeep = Math.ceil(state.pax/PER_JEEP);
  if(state.qty>minJeep){ state.qty--; update(); }
  else toast('Minimal ' + minJeep + ' jeep untuk ' + state.pax + ' penumpang (maks. 4 orang per jeep).');
};

/* ====== OPSI KEDATANGAN ====== */
document.querySelectorAll('.ar').forEach(b=>b.onclick=()=>{
  state.pickup = b.dataset.m==='shuttle';
  document.querySelectorAll('.ar').forEach(x=>x.classList.toggle('sel', x===b));
  $('#pickupBox').hidden = !state.pickup;
  update(); setStep(4);
});

/* ====== LOKASI PENJEMPUTAN OTOMATIS (tanpa peta) ====== */
let calcId = 0;
function haversine(a,b,c,d){
  const R=6371, r=x=>x*Math.PI/180;
  const dLat=r(c-a), dLng=r(d-b);
  const h=Math.sin(dLat/2)**2+Math.cos(r(a))*Math.cos(r(c))*Math.sin(dLng/2)**2;
  return 2*R*Math.asin(Math.sqrt(h));
}
async function computeDistance(lat,lng){
  const id = ++calcId;
  state.lat = lat; state.lng = lng; state.manual = false;
  state.km = null; state.calc = true; update();
  let km, approx=false;
  try{
    const ctl = new AbortController(); const to = setTimeout(()=>ctl.abort(),8000);
    const r = await fetch(`https://router.project-osrm.org/route/v1/driving/${BASECAMP.lng},${BASECAMP.lat};${lng},${lat}?overview=false`,{signal:ctl.signal});
    clearTimeout(to);
    const j = await r.json();
    if(j.code!=='Ok') throw new Error('route');
    km = j.routes[0].distance/1000;
  }catch(e){
    km = haversine(BASECAMP.lat,BASECAMP.lng,lat,lng)*1.3; approx = true; // cadangan: garis lurus x 1.3
  }
  if(id!==calcId) return;
  state.km = Math.round(km*10)/10; state.approx = approx; state.calc = false;
  update();
}
function parseLocation(t){
  try{ t = decodeURIComponent(t.trim()); }catch(e){ t = t.trim(); }
  const m = t.match(/@(-?\d+\.\d+),\s*(-?\d+\.\d+)/)
         || t.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/)
         || t.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+)[,\s+]+(-?\d+\.\d+)/)
         || t.match(/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/);
  if(!m) return null;
  const lat = parseFloat(m[1]), lng = parseFloat(m[2]);
  if(lat<-11.5||lat>6.5||lng<94||lng>141.5) return null; // di luar Indonesia
  return {lat,lng};
}
$('#btnLink').onclick = ()=>{
  const v = $('#link').value.trim();
  if(!v){ toast('Tempel link Google Maps atau koordinat dulu.'); return; }
  const pt = parseLocation(v);
  if(!pt){
    toast(/goo\.gl|maps\.app/i.test(v)
      ? 'Link pendek tidak bisa dibaca. Salin koordinat (angka -7.xxx, 110.xxx) dari Google Maps.'
      : 'Format belum dikenali. Gunakan link lengkap Google Maps atau koordinat, mis. -7.7956, 110.3695');
    return;
  }
  computeDistance(pt.lat, pt.lng);
};
$('#link').addEventListener('keydown', e=>{ if(e.key==='Enter'){ e.preventDefault(); $('#btnLink').click(); } });
$('#btnGps').onclick = ()=>{
  if(!navigator.geolocation){ toast('Browser tidak mendukung lokasi. Gunakan link/koordinat Google Maps.'); return; }
  toast('Mengambil lokasi Anda…');
  navigator.geolocation.getCurrentPosition(
    p=>computeDistance(p.coords.latitude,p.coords.longitude),
    err=>{
      toast(err.code===1 ? 'Izin lokasi diblokir. Klik ikon gembok di address bar > izinkan Lokasi, atau tempel link/koordinat Google Maps.'
        : err.code===2 ? 'Lokasi perangkat tidak terdeteksi. Tempel link/koordinat Google Maps.'
        : 'Waktu pencarian lokasi habis. Coba lagi atau tempel link/koordinat Google Maps.');
    },
    {enableHighAccuracy:true,timeout:10000});
};
$('#btnManual').onclick = ()=>{
  ++calcId; state.manual = true; state.km = null; state.lat = null; state.lng = null; state.calc = false;
  update(); toast('Baik, biaya jemput akan dikonfirmasi admin lewat WhatsApp.');
};

function pickupFee(){
  if(!state.pickup || state.km===null) return 0;
  return Math.ceil(Math.max(0, state.km - FREE_KM)) * EXTRA_PER_KM;
}

/* ====== PEMBAYARAN ====== */
document.querySelectorAll('.po').forEach(b=>b.onclick=()=>{
  state.pay = b.dataset.p;
  document.querySelectorAll('.po').forEach(x=>x.classList.toggle('sel', x===b));
  update();
});

/* ====== RINGKASAN ====== */
function update(){
  const p = PACKAGES.find(x=>x.id===state.pkg);
  const fee = pickupFee();
  const total = p.price * state.qty + fee;
  const due = state.pay==='dp' ? Math.round(total*0.3) : total;

  $('#qty').textContent = state.qty;
  $('#pax').textContent = state.pax;
  $('#jeepHint').textContent = state.qty + ' Jeep × ' + rp(p.price);
  $('#tPkg').textContent = p.name + ' · ' + state.qty + ' jeep · ' + state.pax + ' orang';
  const d = dateEl.value ? new Date(dateEl.value+'T00:00:00').toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'}) : 'Belum pilih tanggal';
  const sObj = SESSIONS.find(s=>s.v===state.session);
  $('#tDate').textContent = d + (sObj ? ' · ' + sObj.label.replace(/^.*\(/,'').replace(')','') : '');

  $('#lJeep').textContent = 'Sewa Jeep' + (state.qty>1 ? ' (' + state.qty + '×)' : '');
  $('#vJeep').textContent = rp(p.price * state.qty);

  // baris penjemputan + biaya tambahan otomatis
  const vp = $('#vPick'); vp.className = '';
  const rowExtra = $('#rowExtra');
  rowExtra.style.display = 'none';
  if(!state.pickup) vp.textContent = 'Tidak dipilih';
  else if(state.manual) vp.textContent = 'Dikonfirmasi admin';
  else if(state.calc) vp.textContent = 'Menghitung…';
  else if(state.km===null) vp.textContent = 'Tentukan lokasi';
  else if(fee===0){ vp.textContent = 'Gratis (' + state.km + ' km)'; vp.className='free'; }
  else {
    const over = Math.ceil(state.km-FREE_KM);
    vp.textContent = 'Jarak ' + state.km + ' km';
    rowExtra.style.display = 'flex';
    $('#lExtra').innerHTML = 'Biaya Tambahan Jemput<small>Gratis ' + FREE_KM + ' km, lebih ' + over + ' km × ' + rp(EXTRA_PER_KM) + '</small>';
    $('#vExtra').textContent = '+ ' + rp(fee);
  }

  // kotak jarak
  const dist = $('#dist');
  dist.classList.toggle('over', state.km!==null && state.km>FREE_KM);
  if(state.manual){
    $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Biaya penjemputan akan dikonfirmasi admin lewat WhatsApp.'; $('#distTag').textContent = 'Via admin';
  } else if(state.calc){
    $('#distKm').textContent = '…'; $('#distTxt').textContent = 'Menghitung jarak rute dari basecamp…'; $('#distTag').textContent = 'Menghitung';
  } else if(state.km===null){
    $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Bagikan lokasi untuk menghitung jarak otomatis.'; $('#distTag').textContent = 'Belum dihitung';
  } else if(state.km<=FREE_KM){
    $('#distKm').textContent = state.km + ' km';
    $('#distTxt').textContent = 'Jarak ' + (state.approx?'perkiraan':'rute jalan') + ' dari basecamp. Masih dalam jangkauan jemput gratis.';
    $('#distTag').textContent = 'GRATIS';
  } else {
    const over = Math.ceil(state.km-FREE_KM);
    $('#distKm').textContent = state.km + ' km';
    $('#distTxt').textContent = 'Lebih ' + over + ' km dari batas gratis × ' + rp(EXTRA_PER_KM) + ' = ' + rp(fee) + (state.approx?' (jarak perkiraan)':'');
    $('#distTag').textContent = '+ ' + rp(fee);
  }

  $('#vTotal').textContent = rp(total);
  $('#mTotal').textContent = rp(total);
  $('#vDue').textContent = rp(due);
  $('#dueLbl').textContent = state.pay==='dp' ? 'Wajib Dibayar Sekarang (DP 30%)' : 'Wajib Dibayar Sekarang';
  $('#dueSub').textContent = state.pay==='dp' ? 'Sisa ' + rp(total-due) : 'Lunas';
}

/* ====== STEPPER ====== */
function setStep(n){
  document.querySelectorAll('.st').forEach(s=>{
    const k = +s.dataset.s;
    s.classList.toggle('on', k===n);
    s.classList.toggle('done', k<n);
    s.querySelector('i').textContent = k<n ? '✓' : k;
  });
}
document.querySelectorAll('.st').forEach(s=>s.onclick=()=>{
  const n = +s.dataset.s;
  document.querySelector(`[data-sec="${n}"]`).scrollIntoView({behavior:'smooth',block:'center'}); setStep(n);
});

/* ====== TOAST ====== */
let tt;
function toast(msg){ const t=$('#toast'); t.textContent=msg; t.classList.add('show'); clearTimeout(tt); tt=setTimeout(()=>t.classList.remove('show'),3200); }

/* ====== KIRIM ====== */
$('#submit').onclick = ()=>{
  const name = $('#name'), wa = $('#wa'), addr = $('#addr');
  [name,wa,addr].forEach(x=>x.classList.remove('err'));
  if(!dateEl.value){ toast('Pilih tanggal kunjungan dulu ya.'); return dateEl.focus(); }
  if(!state.session){ toast('Pilih sesi waktu keberangkatan.'); return $('#session').focus(); }
  if(state.pickup){
    if(addr.value.trim().length<3){ addr.classList.add('err'); toast('Isi nama hotel / alamat penjemputan.'); return addr.focus(); }
    if(state.calc){ toast('Jarak penjemputan masih dihitung, tunggu sebentar.'); return; }
    if(state.km===null && !state.manual){ toast('Bagikan lokasi atau tempel link Google Maps agar jarak bisa dihitung.'); return $('#btnGps').focus(); }
  }
  if(name.value.trim().length<3){ name.classList.add('err'); toast('Isi nama lengkap Anda.'); return name.focus(); }
  if(!/^(\+?62|0)8\d{7,12}$/.test(wa.value.replace(/[\s-]/g,''))){ wa.classList.add('err'); toast('Nomor WhatsApp belum valid.'); return wa.focus(); }

  const p = PACKAGES.find(x=>x.id===state.pkg);
  const fee = pickupFee();
  const total = p.price*state.qty + fee;
  const due = state.pay==='dp' ? Math.round(total*0.3) : total;
  const tgl = new Date(dateEl.value+'T00:00:00').toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
  const sess = SESSIONS.find(s=>s.v===state.session).label;
  const text =
`Halo Jeep Wukirsari, saya ingin booking:

*Nama:* ${name.value.trim()}
*WhatsApp:* ${wa.value.trim()}
*Paket:* ${p.name}
*Penumpang:* ${state.pax} orang (${state.qty} jeep)
*Tanggal:* ${tgl}
*Sesi:* ${sess}
*Kedatangan:* ${state.pickup
  ? 'Shuttle - ' + addr.value.trim() + (state.manual
      ? '\n*Biaya jemput:* mohon dikonfirmasi admin (lokasi tidak terdeteksi)'
      : '\n*Jarak:* ' + state.km + ' km (' + (fee?'biaya jemput '+rp(fee):'gratis') + ')\n*Titik jemput:* https://maps.google.com/?q=' + state.lat + ',' + state.lng)
  : 'Datang sendiri ke basecamp'}

*Total:* ${rp(total)}
*Skema:* ${state.pay==='dp' ? 'DP 30%' : 'Lunas'}
*Wajib dibayar sekarang:* ${rp(due)}`;
  toast('Mengirim pesanan ke WhatsApp admin…');
  setTimeout(()=>window.open('https://wa.me/'+WA_NUMBER+'?text='+encodeURIComponent(text),'_blank'),500);
};

renderSessions(); update();
</script>
</body>
</html>
