@extends('layouts.app')

@section('title', 'Formulir Pemesanan Jeep | Jeep Wukirsari')

@section('content')
<style>
.bk{--g9:#14301b;--g8:#1d4026;--g7:#255231;--g6:#2f6a3f;--g1:#e3efe5;--g05:#f1f6f2;--cream:#f5f4ee;--ink:#15221a;--muted:#6b7a70;--line:#e4e8e2;--accent:#e8a33d;--danger:#c0392b;--shadow:0 10px 30px -12px rgba(20,48,27,.18);background:var(--cream);color:var(--ink);padding:36px 0 70px;line-height:1.5}
.bk *{box-sizing:border-box}
.bk-in{max-width:1200px;margin:0 auto;padding:0 24px}
.bk-head small{font-size:12px;font-weight:800;letter-spacing:1.5px;color:var(--g8)}
.bk-head h1{font-size:clamp(28px,4vw,38px);font-weight:800;letter-spacing:-.5px;margin:6px 0}
.bk-head p{color:var(--muted)}
.bk-err{margin-top:18px;background:#fdecea;border:1px solid #f5c2bd;color:#8e2a20;border-radius:14px;padding:12px 16px;font-size:13.5px}
.bk-err ul{margin:0;padding-left:18px}
.bk-grid{display:grid;grid-template-columns:minmax(0,1fr) 380px;gap:28px;align-items:start;margin-top:26px}
.bk-stepper{grid-column:1/-1;display:flex;gap:10px;background:#fff;border-radius:999px;padding:8px;box-shadow:var(--shadow);overflow-x:auto}
.bk-st{flex:1;display:flex;align-items:center;gap:10px;padding:10px 16px;border-radius:999px;font-size:13px;font-weight:700;color:var(--muted);white-space:nowrap;transition:.25s;cursor:pointer;border:0;background:none}
.bk-st i{font-style:normal;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;background:var(--g1);color:var(--g7);font-size:12px}
.bk-st.on{background:var(--g8);color:#fff}
.bk-st.on i{background:var(--accent);color:var(--g9)}
.bk-st.done i{background:var(--g6);color:#fff}
.bk .card{background:#fff;border-radius:20px;padding:28px;box-shadow:var(--shadow);margin-bottom:22px}
.bk .card h2{display:flex;align-items:center;gap:12px;font-size:19px;font-weight:800;margin:0 0 6px}
.bk .num{width:30px;height:30px;border-radius:50%;background:var(--g8);color:#fff;display:grid;place-items:center;font-size:14px}
.bk .sub{color:var(--muted);font-size:14px;margin:0 0 20px 42px}
.bk .pk{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:14px}
.bk .pkg{position:relative;display:flex;flex-direction:column;gap:6px;text-align:left;padding:18px;border:1.5px solid var(--line);border-radius:16px;background:#fff;transition:.2s;cursor:pointer}
.bk .pkg:hover{border-color:var(--g6);transform:translateY(-3px);box-shadow:0 12px 24px -14px rgba(20,48,27,.4)}
.bk .pkg.sel{border-color:var(--g8);background:var(--g05);box-shadow:0 0 0 3px rgba(29,64,38,.12)}
.bk .pkg input{display:none}
.bk .pkg h3{font-size:15px;font-weight:800;margin:0;padding-right:26px}
.bk .pkg .pr{font-size:19px;font-weight:800;color:var(--g7)}
.bk .pkg .meta{font-size:12.5px;color:var(--muted)}
.bk .pkg .check{position:absolute;top:14px;right:14px;width:22px;height:22px;border-radius:50%;background:var(--g8);color:#fff;font-size:12px;display:none;place-items:center}
.bk .pkg.sel .check{display:grid}
.bk .grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.bk .f{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}
.bk .f label{font-size:13px;font-weight:700;color:#2e3d33;margin:0}
.bk .in{width:100%;padding:13px 15px;border:1.5px solid var(--line);border-radius:12px;background:#fff;outline:none;transition:.2s;font-size:14.5px;font-family:inherit;color:var(--ink)}
.bk .in:focus{border-color:var(--g6);box-shadow:0 0 0 4px rgba(47,106,63,.12)}
.bk .in.err{border-color:var(--danger)}
.bk select.in{appearance:none;-webkit-appearance:none;padding-right:42px;cursor:pointer;background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%231d4026' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 16px center}
.bk .hint{font-size:12.5px;color:var(--muted)}
.bk .qty{display:inline-flex;align-items:center;border:1.5px solid var(--line);border-radius:12px;overflow:hidden;align-self:flex-start}
.bk .qty button{width:44px;height:44px;font-size:20px;font-weight:700;color:var(--g7);background:none;border:0;cursor:pointer}
.bk .qty button:hover{background:var(--g05)}
.bk .qty output{min-width:52px;text-align:center;font-weight:800}
.bk .arrive{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px}
.bk .ar{padding:14px;border:1.5px solid var(--line);border-radius:14px;font-weight:700;font-size:13.5px;background:#fff;transition:.2s;cursor:pointer;color:var(--ink)}
.bk .ar:hover{border-color:var(--g6)}
.bk .ar.sel{border-color:var(--g8);background:var(--g05);color:var(--g8);box-shadow:0 0 0 3px rgba(29,64,38,.1)}
.bk .search{display:flex;gap:10px}
.bk .search .in{flex:1}
.bk .btn-s{padding:0 18px;border-radius:12px;background:var(--g8);color:#fff;font-weight:700;font-size:14px;white-space:nowrap;border:0;cursor:pointer;min-height:46px}
.bk .btn-s:hover{background:var(--g7)}
.bk .results{border:1.5px solid var(--line);border-radius:12px;background:#fff;overflow:hidden;max-height:240px;overflow-y:auto;box-shadow:var(--shadow);margin-top:2px}
.bk .res{display:block;width:100%;text-align:left;padding:11px 14px;font-size:13px;border:0;border-bottom:1px solid var(--line);background:#fff;color:var(--ink);cursor:pointer;line-height:1.4}
.bk .res:last-child{border-bottom:0}
.bk .res:hover{background:var(--g05)}
.bk details.alt{margin:0 0 16px;border:1.5px solid var(--line);border-radius:14px;padding:12px 16px;background:#fff}
.bk details.alt summary{font-weight:700;font-size:13.5px;cursor:pointer;color:var(--g8)}
.bk details.alt .f:last-child{margin-bottom:0}
.bk .dist small.pl{margin-top:4px;color:var(--g7);font-weight:600}
.bk .dist{display:flex;align-items:center;justify-content:space-between;gap:14px;border-radius:14px;padding:14px 16px;background:var(--g05);border:1.5px solid #cfe0d2;margin-bottom:16px}
.bk .dist.over{background:#fff7e8;border-color:#f3dcae}
.bk .dist .k{font-size:22px;font-weight:800;color:var(--g8)}
.bk .dist.over .k{color:#9a6a0c}
.bk .dist small{display:block;color:var(--muted);font-size:12.5px}
.bk .dist .tag{font-size:12px;font-weight:800;padding:6px 12px;border-radius:999px;background:var(--g8);color:#fff;white-space:nowrap}
.bk .dist.over .tag{background:var(--accent);color:var(--g9)}
.bk .sum{position:sticky;top:100px}
.bk .sum .card{margin:0;padding:26px}
.bk .sum h3{font-size:18px;font-weight:800;padding-bottom:14px;border-bottom:1px solid var(--line);margin:0 0 14px}
.bk .ticket{background:var(--g05);border:1.5px dashed #b8cfbc;border-radius:14px;padding:14px;margin-bottom:14px;font-size:13px}
.bk .ticket b{display:block;font-size:15px;color:var(--g8)}
.bk .ticket span{color:var(--muted)}
.bk .row{display:flex;justify-content:space-between;gap:10px;padding:7px 0;font-size:14px;color:#52625a}
.bk .row b{color:var(--ink);font-weight:700;text-align:right}
.bk .row b.free{color:#0f8a52}
.bk .row.extra{background:#fff7e8;border:1px solid #f3dcae;border-radius:10px;padding:9px 12px;margin:4px 0;color:#7a5410}
.bk .row.extra b{color:#9a6a0c}
.bk .row.extra small{display:block;font-size:11.5px;opacity:.85}
.bk .total{display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--line);margin-top:8px;padding-top:16px}
.bk .total span{font-weight:800}
.bk .total strong{font-size:27px;font-weight:800;color:var(--g8);letter-spacing:-.5px}
.bk .pay{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:18px 0 12px}
.bk .po{text-align:left;padding:12px;border:1.5px solid var(--line);border-radius:14px;transition:.2s;background:#fff;cursor:pointer;color:var(--ink)}
.bk .po b{display:block;font-size:13px}
.bk .po small{font-size:11.5px;color:var(--muted)}
.bk .po.sel{border-color:var(--g8);background:var(--g05);box-shadow:0 0 0 3px rgba(29,64,38,.1)}
.bk .due{background:var(--g9);color:#fff;border-radius:14px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.bk .due small{display:block;color:#a9c4af;font-size:12px}
.bk .due strong{font-size:20px}
.bk .due span{font-size:12px;color:#a9c4af;text-align:right}
.bk .cta{width:100%;background:linear-gradient(135deg,var(--g7),var(--g9));color:#fff;padding:16px;border-radius:14px;font-weight:800;font-size:15.5px;display:flex;justify-content:center;align-items:center;gap:10px;transition:.2s;box-shadow:0 12px 22px -12px rgba(20,48,27,.7);border:0;cursor:pointer}
.bk .cta:hover{transform:translateY(-2px)}
.bk .cta:disabled{opacity:.7;cursor:wait;transform:none}
.bk .secure{display:flex;justify-content:center;gap:14px;flex-wrap:wrap;margin-top:14px;font-size:12px;color:var(--muted);font-weight:600}
.bk-toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,120px);background:var(--g9);color:#fff;padding:13px 20px;border-radius:12px;font-size:14px;font-weight:600;transition:.35s;z-index:1000;max-width:90%}
.bk-toast.show{transform:translate(-50%,0)}
.bk-mbar{display:none}
@media(max-width:980px){
  .bk-grid{grid-template-columns:1fr}
  .bk .sum{position:static}
  .bk{padding-bottom:100px}
  .bk-mbar{display:flex;position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid var(--line);padding:12px 18px;justify-content:space-between;align-items:center;z-index:60;box-shadow:0 -8px 24px -12px rgba(0,0,0,.2)}
  .bk-mbar small{display:block;color:var(--muted);font-size:11.5px}
  .bk-mbar strong{font-size:18px;color:var(--g8)}
  .bk-mbar a{background:var(--g8);color:#fff;padding:12px 20px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none}
}
@media(max-width:560px){
  .bk .grid2,.bk .arrive{grid-template-columns:1fr}
  .bk .card{padding:20px}
  .bk .sub{margin-left:0}
  .bk .search{flex-direction:column}
}
</style>

<div class="bk" id="bk"
     data-base-lat="{{ \App\Http\Controllers\BookingController::BASECAMP_LAT }}"
     data-base-lng="{{ \App\Http\Controllers\BookingController::BASECAMP_LNG }}"
     data-free-km="{{ \App\Http\Controllers\BookingController::PICKUP_FREE_KM }}"
     data-per-jeep="{{ \App\Http\Controllers\BookingController::PICKUP_EXTRA_PER_JEEP }}"
     data-geo-url="{{ \Illuminate\Support\Facades\Route::has('booking.location') ? route('booking.location') : '' }}"
     data-base-zone="{{ $baseZone->id ?? '' }}"
     data-shuttle-zone="{{ $shuttleZone->id ?? '' }}">
<div class="bk-in">

  <div class="bk-head">
    <small>FORMULIR RESERVASI</small>
    <h1>Pemesanan Jeep Wukirsari</h1>
    <p>Pilih paket, jadwal keberangkatan, dan opsi pembayaran aman (Full atau Uang Muka DP 30%).</p>
  </div>

  @if ($errors->any())
  <div class="bk-err">
    <ul>
      @foreach ($errors->all() as $err)
      <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="bk-grid">
    @csrf
    <input type="hidden" name="passenger_count" id="passenger_count" value="4">
    <input type="hidden" name="jeep_quantity" id="jeep_quantity" value="1">
    <input type="hidden" name="arrival_type" id="arrival_type" value="self_drive">
    <input type="hidden" name="pickup_zone_id" id="pickup_zone_id" value="{{ $baseZone->id ?? '' }}">
    <input type="hidden" name="pickup_distance_km" id="pickup_distance_km" value="">
    <input type="hidden" name="pickup_lat" id="pickup_lat" value="">
    <input type="hidden" name="pickup_lng" id="pickup_lng" value="">
    <input type="hidden" name="payment_mode" id="payment_mode" value="FULL">

    <div class="bk-stepper" id="bkStepper">
      <button type="button" class="bk-st on" data-s="1"><i>1</i>Paket</button>
      <button type="button" class="bk-st" data-s="2"><i>2</i>Jadwal</button>
      <button type="button" class="bk-st" data-s="3"><i>3</i>Kapasitas</button>
      <button type="button" class="bk-st" data-s="4"><i>4</i>Kontak</button>
    </div>

    <div>
      <!-- 1. PAKET -->
      <section class="card" data-sec="1">
        <h2><span class="num">1</span>Pilih Paket Wisata</h2>
        <p class="sub">Harga per unit jeep.</p>
        <div class="pk">
          @foreach($packages as $pkg)
          @php $isSun = str_contains(strtolower(($pkg->slug ?? '') . ' ' . $pkg->name), 'sunrise'); @endphp
          <label class="pkg {{ $pkg->id == $selectedPackage->id ? 'sel' : '' }}">
            <input type="radio" name="package_id" value="{{ $pkg->id }}"
                   data-price="{{ (int) $pkg->price_per_jeep }}"
                   data-name="{{ $pkg->name }}"
                   data-sunrise="{{ $isSun ? 1 : 0 }}"
                   {{ $pkg->id == $selectedPackage->id ? 'checked' : '' }}>
            <h3>{{ $pkg->name }}</h3>
            <div class="pr">Rp {{ number_format($pkg->price_per_jeep, 0, ',', '.') }}</div>
            <div class="meta">{{ $pkg->duration_minutes }} menit</div>
            <span class="check">✓</span>
          </label>
          @endforeach
        </div>
      </section>

      <!-- 2. JADWAL -->
      <section class="card" data-sec="2">
        <h2><span class="num">2</span>Pilih Tanggal &amp; Jam Trip</h2>
        <p class="sub">Pilih hari dan sesi keberangkatan.</p>
        <div class="grid2">
          <div class="f">
            <label for="trip_date">Tanggal Kunjungan</label>
            <input type="date" name="trip_date" id="trip_date" class="in" min="{{ date('Y-m-d') }}" value="{{ old('trip_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
          </div>
          <div class="f">
            <label for="slot_id">Sesi Waktu Keberangkatan</label>
            <select name="slot_id" id="slot_id" class="in" required>
              @foreach($slots as $slot)
              <option value="{{ $slot->id }}" data-sunrise="{{ str_contains(strtolower($slot->slot_label), 'sunrise') ? 1 : 0 }}" {{ old('slot_id') == $slot->id ? 'selected' : '' }}>{{ $slot->slot_label }}</option>
              @endforeach
            </select>
            <span class="hint" id="slotHint"></span>
          </div>
        </div>
      </section>

      <!-- 3. KAPASITAS -->
      <section class="card" data-sec="3">
        <h2><span class="num">3</span>Kapasitas &amp; Jumlah Jeep</h2>
        <p class="sub">Satu jeep dapat memuat maksimal 4 penumpang.</p>
        <div class="grid2">
          <div class="f">
            <label>Jumlah Penumpang (Orang)</label>
            <div class="qty"><button type="button" id="paxMinus" aria-label="Kurangi penumpang">−</button><output id="paxOut">4</output><button type="button" id="paxPlus" aria-label="Tambah penumpang">+</button></div>
            <span class="hint">*Maksimal 4 penumpang per armada jeep.</span>
          </div>
          <div class="f">
            <label>Jumlah Armada Jeep</label>
            <div class="qty"><button type="button" id="jeepMinus" aria-label="Kurangi jeep">−</button><output id="jeepOut">1</output><button type="button" id="jeepPlus" aria-label="Tambah jeep">+</button></div>
            <span class="hint" id="jeepHint" style="color:var(--g7);font-weight:600"></span>
          </div>
        </div>
      </section>

      <!-- 4. KEDATANGAN & KONTAK -->
      <section class="card" data-sec="4">
        <h2><span class="num">4</span>Opsi Kedatangan &amp; Kontak</h2>
        <p class="sub">Pilih cara Anda tiba, lalu isi kontak untuk pengiriman tiket.</p>
        <div class="arrive">
          <button type="button" class="ar sel" data-m="self">Datang Sendiri ke Basecamp</button>
          <button type="button" class="ar" data-m="shuttle">Layanan Antar-Jemput (Shuttle)</button>
        </div>

        <div id="pickupBox" hidden>
          <div class="f">
            <label for="pickup_address">Nama Hotel / Villa / Alamat Penjemputan *</label>
            <div class="search">
              <input type="text" name="pickup_address" id="pickup_address" class="in" maxlength="300" value="{{ old('pickup_address') }}" placeholder="Contoh: Villa Padi Cangkringan, Yogyakarta" autocomplete="off">
              <button type="button" class="btn-s" id="btnFind">Cari Lokasi</button>
            </div>
            <div class="results" id="results" hidden></div>
            <span class="hint" id="findHint">Ketik nama hotel atau villa, lalu pilih lokasinya. Jarak dari basecamp dihitung otomatis.</span>
          </div>

          <div class="dist" id="dist">
            <div><div class="k" id="distKm">—</div><small id="distTxt">Cari hotel atau villa untuk menghitung jarak otomatis.</small><small class="pl" id="pickedLbl"></small></div>
            <span class="tag" id="distTag">Belum dihitung</span>
          </div>

          <details class="alt">
            <summary>Lokasi tidak ketemu? Tentukan titik dengan cara lain</summary>
            <div class="f" style="margin:14px 0 12px">
              <label>Pakai lokasi saya saat ini</label>
              <button type="button" class="btn-s" id="btnGps" style="padding:14px">Bagikan Lokasi Saya Sekarang</button>
              <span class="hint">Tekan saat Anda berada di lokasi jemput.</span>
            </div>
            <div class="f">
              <label for="mapLink">Atau tempel link Google Maps / koordinat</label>
              <div class="search">
                <input id="mapLink" class="in" placeholder="https://www.google.com/maps/... atau -7.7956, 110.3695">
                <button type="button" class="btn-s" id="btnLink">Hitung</button>
              </div>
              <span class="hint">Di Google Maps: tahan lama pada lokasi hotel, lalu salin angka koordinat yang muncul di bagian atas.</span>
            </div>
          </details>
        </div>

        <div class="grid2">
          <div class="f">
            <label for="customer_name">Nama Lengkap Pemesan *</label>
            <input type="text" name="customer_name" id="customer_name" class="in" required maxlength="100" value="{{ old('customer_name') }}" placeholder="Contoh: Budi Santoso" autocomplete="name">
          </div>
          <div class="f">
            <label for="customer_phone">Nomor WhatsApp Aktif *</label>
            <input type="tel" name="customer_phone" id="customer_phone" class="in" required maxlength="20" value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" autocomplete="tel" inputmode="tel">
            <span class="hint">*Tiket QR Code dikirim ke nomor ini.</span>
          </div>
        </div>
      </section>
    </div>

    <!-- RINGKASAN -->
    <aside class="sum" id="bkSummary">
      <div class="card">
        <h3>Ringkasan Tagihan</h3>
        <div class="ticket"><b id="tPkg">—</b><span id="tDate">—</span></div>
        <div class="row"><span id="lJeep">Sewa Jeep</span><b id="vJeep">Rp 0</b></div>
        <div class="row"><span>Layanan Penjemputan</span><b id="vPick">Tidak dipilih</b></div>
        <div class="row extra" id="rowExtra" style="display:none"><span id="lExtra">Biaya Tambahan Jemput</span><b id="vExtra">Rp 0</b></div>
        <div class="row"><span>Asuransi &amp; BBM</span><b class="free">Gratis (Termasuk)</b></div>
        <div class="total"><span>Total Biaya</span><strong id="vTotal">Rp 0</strong></div>

        <div style="margin-top:20px;font-size:13px;font-weight:800">Pilih Skema Pembayaran</div>
        <div class="pay">
          <button type="button" class="po sel" data-p="FULL"><b>Bayar Lunas (100%)</b><small>Langsung beres</small></button>
          <button type="button" class="po" data-p="DP"><b>Uang Muka (DP 30%)</b><small>Pelunasan di basecamp</small></button>
        </div>
        <div class="due"><div><small>Wajib Dibayar Sekarang</small><strong id="vDue">Rp 0</strong></div><span id="dueSub"></span></div>
        <button class="cta" id="btnSubmit" type="submit"><span>Konfirmasi &amp; Terbitkan E-Tiket →</span></button>
        <div class="secure"><span>Data aman</span><span>Reschedule gratis H-2</span><span>Tiket resmi</span></div>
      </div>
    </aside>
  </form>
</div>

<div class="bk-mbar"><div><small>Total biaya</small><strong id="mTotal">Rp 0</strong></div><a href="#bkSummary">Lihat Ringkasan</a></div>
<div class="bk-toast" id="bkToast"></div>
</div>

<script>
(function () {
  var root = document.getElementById('bk');
  var CFG = {
    lat: parseFloat(root.dataset.baseLat),
    lng: parseFloat(root.dataset.baseLng),
    free: parseFloat(root.dataset.freeKm),
    perJeep: parseInt(root.dataset.perJeep, 10)
  };
  var ZONE = { base: root.dataset.baseZone, shuttle: root.dataset.shuttleZone };
  var GEO_URL = root.dataset.geoUrl || '';
  var $ = function (s) { return root.querySelector(s); };
  var $$ = function (s) { return Array.prototype.slice.call(root.querySelectorAll(s)); };
  var rp = function (n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); };
  var PER_JEEP = 4;
  var st = { pax: 4, jeep: 1, shuttle: false, pay: 'FULL', km: null, approx: false, calc: false, label: '' };
  var calcId = 0, toastT;

  function toast(msg) {
    var t = $('#bkToast'); t.textContent = msg; t.classList.add('show');
    clearTimeout(toastT); toastT = setTimeout(function () { t.classList.remove('show'); }, 3600);
  }
  function setStep(n) {
    $$('.bk-st').forEach(function (s) {
      var k = +s.dataset.s;
      s.classList.toggle('on', k === n);
      s.classList.toggle('done', k < n);
      s.querySelector('i').textContent = k < n ? '✓' : k;
    });
  }
  function pkg() { return $$('input[name=package_id]').filter(function (r) { return r.checked; })[0]; }
  function isoToday() { var d = new Date(); return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10); }

  /* ===== PAKET & SESI ===== */
  var slotSel = $('#slot_id'), dateEl = $('#trip_date');
  $$('#slot_id option').forEach(function (o) {
    var m = o.textContent.match(/(\d{1,2})[:.](\d{2})/);
    o.dataset.min = m ? (parseInt(m[1], 10) * 60 + parseInt(m[2], 10)) : '';
  });
  function updateSlots() {
    var p = pkg(), isSun = p && p.dataset.sunrise === '1';
    var opts = Array.prototype.slice.call(slotSel.options);
    var hasSun = opts.some(function (o) { return o.dataset.sunrise === '1'; });
    var today = dateEl.value === isoToday();
    var now = new Date(), nowMin = now.getHours() * 60 + now.getMinutes();
    var firstOk = null;
    opts.forEach(function (o) {
      var sun = o.dataset.sunrise === '1';
      var allowed = !hasSun ? true : (isSun ? sun : !sun);
      var passed = today && o.dataset.min !== '' && Number(o.dataset.min) <= nowMin;
      o.disabled = !allowed || passed;
      o.hidden = !allowed;
      if (!o.disabled && !firstOk) firstOk = o;
    });
    var cur = slotSel.options[slotSel.selectedIndex];
    if (!cur || cur.disabled) slotSel.value = firstOk ? firstOk.value : '';
    $('#slotHint').textContent = !firstOk ? 'Semua sesi hari ini sudah lewat, pilih tanggal lain.'
      : (isSun && hasSun ? 'Sunrise Trip hanya tersedia di sesi Sunrise Tour.' : '');
  }
  $$('input[name=package_id]').forEach(function (r) {
    r.addEventListener('change', function () {
      $$('.pkg').forEach(function (l) { l.classList.toggle('sel', l.contains(r) && r.checked); });
      updateSlots(); update(); setStep(2);
    });
  });
  dateEl.addEventListener('change', function () { updateSlots(); update(); setStep(2); });
  slotSel.addEventListener('change', function () { update(); setStep(3); });

  /* ===== KAPASITAS ===== */
  function minJeep() { return Math.ceil(st.pax / PER_JEEP); }
  $('#paxPlus').onclick = function () { if (st.pax < 40) { st.pax++; if (st.jeep < minJeep()) st.jeep = minJeep(); update(); setStep(3); } };
  $('#paxMinus').onclick = function () { if (st.pax > 1) { st.pax--; update(); setStep(3); } };
  $('#jeepPlus').onclick = function () { if (st.jeep < 10) { st.jeep++; update(); setStep(3); } };
  $('#jeepMinus').onclick = function () {
    if (st.jeep > minJeep()) { st.jeep--; update(); }
    else toast('Minimal ' + minJeep() + ' jeep untuk ' + st.pax + ' penumpang (maks. 4 orang per jeep).');
  };

  /* ===== KEDATANGAN ===== */
  $$('.ar').forEach(function (b) {
    b.onclick = function () {
      st.shuttle = b.dataset.m === 'shuttle';
      $$('.ar').forEach(function (x) { x.classList.toggle('sel', x === b); });
      $('#pickupBox').hidden = !st.shuttle;
      $('#arrival_type').value = st.shuttle ? 'pickup' : 'self_drive';
      $('#pickup_zone_id').value = st.shuttle ? (ZONE.shuttle || ZONE.base) : ZONE.base;
      update(); setStep(4);
    };
  });

  /* ===== LOKASI & JARAK OTOMATIS ===== */
  function haversine(a, b, c, d) {
    var R = 6371, r = function (x) { return x * Math.PI / 180; };
    var dLat = r(c - a), dLng = r(d - b);
    var h = Math.pow(Math.sin(dLat / 2), 2) + Math.cos(r(a)) * Math.cos(r(c)) * Math.pow(Math.sin(dLng / 2), 2);
    return 2 * R * Math.asin(Math.sqrt(h));
  }
  function computeDistance(lat, lng, label) {
    var id = ++calcId;
    st.label = label || ''; st.km = null; st.calc = true;
    $('#pickup_lat').value = lat; $('#pickup_lng').value = lng; $('#pickup_distance_km').value = '';
    update();
    var ctl = new AbortController(), to = setTimeout(function () { ctl.abort(); }, 8000);
    var url = 'https://router.project-osrm.org/route/v1/driving/' + CFG.lng + ',' + CFG.lat + ';' + lng + ',' + lat + '?overview=false';
    fetch(url, { signal: ctl.signal })
      .then(function (r) { return r.json(); })
      .then(function (j) { if (j.code !== 'Ok') throw new Error('route'); return { km: j.routes[0].distance / 1000, approx: false }; })
      .catch(function () { return { km: haversine(CFG.lat, CFG.lng, lat, lng) * 1.3, approx: true }; })
      .then(function (res) {
        clearTimeout(to);
        if (id !== calcId) return;
        st.km = Math.round(res.km * 10) / 10; st.approx = res.approx; st.calc = false;
        $('#pickup_distance_km').value = st.km;
        update();
      });
  }
  function parseLocation(t) {
    try { t = decodeURIComponent(t.trim()); } catch (e) { t = t.trim(); }
    var m = t.match(/\x40(-?\d+\.\d+),\s*(-?\d+\.\d+)/)
         || t.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/)
         || t.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+)[,\s+]+(-?\d+\.\d+)/)
         || t.match(/^(-?\d{1,2}\.\d+)[,\s]+(-?\d{2,3}\.\d+)$/);
    if (!m) return null;
    var lat = parseFloat(m[1]), lng = parseFloat(m[2]);
    if (lat < -11.5 || lat > 6.5 || lng < 94 || lng > 141.5) return null;
    return { lat: lat, lng: lng };
  }
  $('#btnLink').onclick = function () {
    var v = $('#mapLink').value.trim();
    if (!v) { toast('Tempel link Google Maps atau koordinat dulu.'); return; }
    var pt = parseLocation(v);
    if (!pt) {
      toast(/goo\.gl|maps\.app/i.test(v)
        ? 'Link pendek tidak bisa dibaca. Salin koordinat (angka -7.xxx, 110.xxx) dari Google Maps.'
        : 'Format belum dikenali. Gunakan link lengkap Google Maps atau koordinat, mis. -7.7956, 110.3695');
      return;
    }
    computeDistance(pt.lat, pt.lng, 'Koordinat dari link Google Maps');
  };
  $('#mapLink').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); $('#btnLink').click(); } });
  $('#btnGps').onclick = function () {
    if (!navigator.geolocation) { toast('Browser tidak mendukung lokasi. Gunakan link/koordinat Google Maps.'); return; }
    toast('Mengambil lokasi Anda…');
    navigator.geolocation.getCurrentPosition(
      function (p) { computeDistance(p.coords.latitude, p.coords.longitude, 'Lokasi GPS Anda'); },
      function (err) {
        toast(err.code === 1 ? 'Izin lokasi diblokir. Klik ikon gembok di address bar > izinkan Lokasi, atau tempel link/koordinat Google Maps.'
          : err.code === 2 ? 'Lokasi perangkat tidak terdeteksi. Tempel link/koordinat Google Maps.'
          : 'Waktu pencarian lokasi habis. Coba lagi atau tempel link/koordinat Google Maps.');
      },
      { enableHighAccuracy: true, timeout: 10000 });
  };

  /* ===== CARI HOTEL / VILLA ===== */
  var findT, findCtl, found = [];
  var addrEl = $('#pickup_address'), resEl = $('#results'), findHint = $('#findHint');
  function hideResults() { resEl.hidden = true; resEl.innerHTML = ''; }
  function pick(item) {
    hideResults();
    findHint.textContent = 'Lokasi dipilih. Jarak dihitung dari basecamp.';
    computeDistance(item.lat, item.lng, item.label);
  }
  function showResults(list) {
    found = list; resEl.innerHTML = '';
    list.forEach(function (it, i) {
      var b = document.createElement('button');
      b.type = 'button'; b.className = 'res'; b.textContent = it.label; b.dataset.i = i;
      resEl.appendChild(b);
    });
    resEl.hidden = false;
  }
  resEl.addEventListener('click', function (e) {
    var b = e.target.closest('.res'); if (!b) return;
    var it = found[+b.dataset.i]; if (it) pick(it);
  });
  function find(auto) {
    var q = addrEl.value.trim();
    if (q.length < 3) { if (!auto) toast('Ketik nama hotel atau villa dulu.'); return; }
    if (!GEO_URL) { if (!auto) toast('Pencarian lokasi belum aktif. Gunakan GPS atau link Google Maps.'); return; }
    if (findCtl) findCtl.abort();
    var ctl = findCtl = new AbortController(), btn = $('#btnFind');
    btn.textContent = 'Mencari…'; findHint.textContent = 'Mencari lokasi…';
    fetch(GEO_URL + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' }, signal: ctl.signal })
      .then(function (r) { if (!r.ok) throw new Error('http'); return r.json(); })
      .then(function (j) {
        var list = (j && j.results) || [];
        if (!list.length) {
          hideResults();
          findHint.textContent = 'Lokasi belum ditemukan. Coba tambah nama daerah (mis. "Cangkringan Sleman"), atau buka "Tentukan titik dengan cara lain" di bawah.';
        } else if (list.length === 1) { pick(list[0]); }
        else { findHint.textContent = 'Pilih lokasi yang sesuai dari daftar di bawah.'; showResults(list); }
      })
      .catch(function (e) {
        if (e.name === 'AbortError') return;
        hideResults(); findHint.textContent = 'Pencarian gagal. Gunakan GPS atau link Google Maps pada "Tentukan titik dengan cara lain".';
      })
      .then(function () { if (findCtl === ctl) btn.textContent = 'Cari Lokasi'; });
  }
  $('#btnFind').onclick = function () { clearTimeout(findT); find(false); };
  addrEl.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); clearTimeout(findT); find(false); } });
  addrEl.addEventListener('input', function () {
    clearTimeout(findT);
    if (!st.shuttle || st.km !== null || st.calc) return;   // cari otomatis hanya sebelum ada lokasi terpilih
    findT = setTimeout(function () { if (addrEl.value.trim().length >= 5) find(true); }, 900);
  });

  /* ===== PEMBAYARAN ===== */
  $$('.po').forEach(function (b) {
    b.onclick = function () {
      st.pay = b.dataset.p; $('#payment_mode').value = st.pay;
      $$('.po').forEach(function (x) { x.classList.toggle('sel', x === b); });
      update();
    };
  });

  /* ===== RINGKASAN ===== */
  function pickupFee() {
    if (!st.shuttle || st.km === null) return 0;
    return st.km > CFG.free ? st.jeep * CFG.perJeep : 0;
  }
  function update() {
    var p = pkg(); if (!p) return;
    var price = Number(p.dataset.price), fee = pickupFee();
    var subtotal = price * st.jeep, total = subtotal + fee;
    var due = st.pay === 'DP' ? Math.floor((total * 30 + 99) / 100) : total;

    $('#passenger_count').value = st.pax; $('#jeep_quantity').value = st.jeep;
    $('#paxOut').textContent = st.pax; $('#jeepOut').textContent = st.jeep;
    $('#jeepHint').textContent = st.jeep + ' Jeep × ' + rp(price);

    var opt = slotSel.options[slotSel.selectedIndex];
    var d = dateEl.value ? new Date(dateEl.value + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) : 'Belum pilih tanggal';
    $('#tPkg').textContent = p.dataset.name + ' · ' + st.jeep + ' jeep · ' + st.pax + ' orang';
    $('#tDate').textContent = d + (opt && opt.value ? ' · ' + opt.textContent.trim() : '');
    $('#lJeep').textContent = 'Sewa Jeep' + (st.jeep > 1 ? ' (' + st.jeep + '×)' : '');
    $('#vJeep').textContent = rp(subtotal);

    var vp = $('#vPick'), rowExtra = $('#rowExtra');
    vp.className = ''; rowExtra.style.display = 'none';
    if (!st.shuttle) vp.textContent = 'Tidak dipilih';
    else if (st.calc) vp.textContent = 'Menghitung…';
    else if (st.km === null) vp.textContent = 'Tentukan lokasi';
    else if (fee === 0) { vp.textContent = 'Gratis (' + st.km + ' km)'; vp.className = 'free'; }
    else {
      vp.textContent = 'Jarak ' + st.km + ' km';
      rowExtra.style.display = 'flex';
      $('#lExtra').innerHTML = 'Biaya Tambahan Jemput<small>Di atas ' + CFG.free + ' km · ' + st.jeep + ' jeep × ' + rp(CFG.perJeep) + '</small>';
      $('#vExtra').textContent = '+ ' + rp(fee);
    }

    var dist = $('#dist');
    $('#pickedLbl').textContent = (st.shuttle && st.label) ? 'Titik jemput: ' + st.label : '';
    dist.classList.toggle('over', st.km !== null && st.km > CFG.free);
    if (st.calc) { $('#distKm').textContent = '…'; $('#distTxt').textContent = 'Menghitung jarak rute dari basecamp…'; $('#distTag').textContent = 'Menghitung'; }
    else if (st.km === null) { $('#distKm').textContent = '—'; $('#distTxt').textContent = 'Cari hotel atau villa untuk menghitung jarak otomatis.'; $('#distTag').textContent = 'Belum dihitung'; }
    else if (st.km <= CFG.free) {
      $('#distKm').textContent = st.km + ' km';
      $('#distTxt').textContent = 'Jarak ' + (st.approx ? 'perkiraan' : 'rute jalan') + ' dari basecamp. Masih dalam jangkauan jemput gratis.';
      $('#distTag').textContent = 'GRATIS';
    } else {
      $('#distKm').textContent = st.km + ' km';
      $('#distTxt').textContent = 'Lebih dari ' + CFG.free + ' km: ' + st.jeep + ' jeep × ' + rp(CFG.perJeep) + ' = ' + rp(fee) + (st.approx ? ' (jarak perkiraan)' : '') + '. Biaya final dihitung ulang oleh sistem.';
      $('#distTag').textContent = '+ ' + rp(fee);
    }

    $('#vTotal').textContent = rp(total); $('#mTotal').textContent = rp(total);
    $('#vDue').textContent = rp(due);
    $('#dueSub').textContent = st.pay === 'DP' ? 'Sisa ' + rp(total - due) + ' di loket' : 'Lunas';
  }

  /* ===== STEPPER ===== */
  $$('.bk-st').forEach(function (s) {
    s.onclick = function () {
      var n = +s.dataset.s;
      $('[data-sec="' + n + '"]').scrollIntoView({ behavior: 'smooth', block: 'center' }); setStep(n);
    };
  });

  /* ===== KIRIM FORM ===== */
  $('#bookingForm').addEventListener('submit', function (e) {
    var name = $('#customer_name'), phone = $('#customer_phone'), addr = $('#pickup_address');
    [name, phone, addr].forEach(function (x) { x.classList.remove('err'); });
    function stop(el, msg) { e.preventDefault(); if (el) { el.classList.add('err'); el.focus(); } toast(msg); }
    if (!slotSel.value) return stop(slotSel, 'Pilih sesi waktu keberangkatan.');
    if (st.shuttle) {
      if (addr.value.trim().length < 3) return stop(addr, 'Isi nama hotel / alamat penjemputan.');
      if (st.calc) return stop(null, 'Jarak penjemputan masih dihitung, tunggu sebentar.');
      if (st.km === null) return stop(addr, 'Cari hotel/villa lalu pilih lokasinya (atau gunakan GPS/link Google Maps) agar jarak bisa dihitung.');
    }
    if (name.value.trim().length < 3) return stop(name, 'Isi nama lengkap Anda.');
    if (!/^(\+?62|0)8\d{7,12}$/.test(phone.value.replace(/[\s-]/g, ''))) return stop(phone, 'Nomor WhatsApp belum valid.');
    var b = $('#btnSubmit'); b.disabled = true; b.firstElementChild.textContent = 'Memproses…';
  });

  updateSlots(); update();
})();
</script>
@endsection