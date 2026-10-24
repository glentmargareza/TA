import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { defaultPackages, formatRupiah } from '@/components/home/PackageSection';
import { ShieldCheck, ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function BookingPage() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  // State Form
  const selectedSlug = searchParams.get('package') || 'adventure';
  const [packageId, setPackageId] = useState(
    defaultPackages.find((p) => p.slug === selectedSlug)?.id || 1
  );
  
  // Tanggal default: Besok
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  const tomorrowStr = tomorrow.toISOString().split('T')[0];
  const [tripDate, setTripDate] = useState(tomorrowStr);
  const [slotTime, setSlotTime] = useState('08:00');

  // Kapasitas & Penumpang
  const [paxCount, setPaxCount] = useState(4);
  const minJeeps = Math.ceil(paxCount / 4);
  const [jeepCount, setJeepCount] = useState(minJeeps);

  // Penjemputan
  const [arrivalType, setArrivalType] = useState<'self_drive' | 'pickup'>('self_drive');
  const [pickupZoneId, setPickupZoneId] = useState(1);
  const [pickupAddress, setPickupAddress] = useState('');

  // Data Kontak
  const [customerName, setCustomerName] = useState('');
  const [customerPhone, setCustomerPhone] = useState('');

  // Pembayaran: DP vs FULL
  const [paymentMode, setPaymentMode] = useState<'FULL' | 'DP'>('FULL');
  const dpPercentage = 30;

  // Otomatis sesuaikan jumlah jeep jika pax bertambah melebihi kapasitas
  useEffect(() => {
    const requiredJeeps = Math.ceil(paxCount / 4);
    if (jeepCount < requiredJeeps) {
      setJeepCount(requiredJeeps);
    }
  }, [paxCount, jeepCount]);

  // Kalkulasi Harga
  const activePackage = defaultPackages.find((p) => p.id === packageId) || defaultPackages[0];
  const packageSubtotal = activePackage.price * jeepCount;
  
  const pickupZones = [
    { id: 1, name: 'Datang Sendiri ke Basecamp Wukirsari', fee: 0 },
    { id: 2, name: 'Area Cangkringan & Pakem (Lokal)', fee: 50000 },
    { id: 3, name: 'Area Sleman & Kota Yogyakarta / Stasiun', fee: 150000 },
    { id: 4, name: 'Area Bandara YIA Kulon Progo', fee: 300000 },
  ];
  const selectedZone = pickupZones.find((z) => z.id === pickupZoneId) || pickupZones[0];
  const pickupFee = arrivalType === 'pickup' ? selectedZone.fee : 0;
  const totalAmount = packageSubtotal + pickupFee;

  const paidAmount = paymentMode === 'FULL' ? totalAmount : Math.round((totalAmount * dpPercentage) / 100);
  const remainingAmount = totalAmount - paidAmount;

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!customerName || !customerPhone) {
      alert('Mohon isi nama lengkap dan nomor WhatsApp aktif Anda.');
      return;
    }

    // Generate kode booking unik
    const randomCode = 'WKR-' + Math.floor(100000 + Math.random() * 900000);
    
    // Redirect ke halaman sukses dengan parameter
    navigate(`/booking/success?code=${randomCode}&name=${encodeURIComponent(customerName)}&pkg=${encodeURIComponent(activePackage.name)}&date=${tripDate}&mode=${paymentMode}&paid=${paidAmount}&remaining=${remainingAmount}`);
  };

  return (
    <div className="py-12 bg-wukirsari-cream min-h-screen">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="mb-8">
          <span className="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">
            FORMULIR RESERVASI
          </span>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
            Pemesanan Jeep Wukirsari
          </h1>
          <p className="text-merapiText-secondary text-sm mt-1">
            Pilih paket, jadwal keberangkatan, dan opsi pembayaran aman (Full atau Uang Muka DP).
          </p>
        </div>

        <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          {/* Kolom Kiri: Input Formulir */}
          <div className="lg:col-span-7 space-y-6">
            
            {/* Step 1: Pilih Paket */}
            <div className="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
              <h3 className="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                <span className="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">1</span>
                <span>Pilih Paket Wisata</span>
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {defaultPackages.map((pkg) => (
                  <button
                    type="button"
                    key={pkg.id}
                    onClick={() => setPackageId(pkg.id)}
                    className={cn(
                      "p-3.5 rounded-2xl border text-left transition-all",
                      packageId === pkg.id
                        ? "border-wukirsari-primary bg-wukirsari-sage/40 ring-1 ring-wukirsari-primary"
                        : "border-wukirsari-border hover:bg-slate-50"
                    )}
                  >
                    <span className="block text-xs font-bold text-merapiText-primary">{pkg.name}</span>
                    <span className="block text-xs font-bold text-wukirsari-primary mt-1">{formatRupiah(pkg.price)}</span>
                    <span className="block text-[11px] text-merapiText-muted mt-0.5">{pkg.duration}</span>
                  </button>
                ))}
              </div>
            </div>

            {/* Step 2: Jadwal & Sesi Waktu */}
            <div className="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
              <h3 className="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                <span className="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">2</span>
                <span>Pilih Tanggal & Jam Trip</span>
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1.5">
                    Tanggal Kunjungan
                  </label>
                  <input
                    type="date"
                    min={new Date().toISOString().split('T')[0]}
                    value={tripDate}
                    onChange={(e) => setTripDate(e.target.value)}
                    required
                    className="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1.5">
                    Sesi Waktu Keberangkatan
                  </label>
                  <select
                    value={slotTime}
                    onChange={(e) => setSlotTime(e.target.value)}
                    className="w-full px-4 py-2.5 rounded-xl border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary"
                  >
                    <option value="04:30">04:30 WIB (Sunrise Merapi)</option>
                    <option value="08:00">08:00 WIB (Pagi 1)</option>
                    <option value="10:30">10:30 WIB (Pagi 2)</option>
                    <option value="13:30">13:30 WIB (Siang / Sore)</option>
                  </select>
                </div>
              </div>
            </div>

            {/* Step 3: Jumlah Penumpang & Armada */}
            <div className="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
              <h3 className="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                <span className="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">3</span>
                <span>Kapasitas & Jumlah Jeep</span>
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1.5">
                    Jumlah Penumpang (Orang)
                  </label>
                  <div className="flex items-center gap-3">
                    <button
                      type="button"
                      onClick={() => setPaxCount(Math.max(1, paxCount - 1))}
                      className="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold"
                    >
                      -
                    </button>
                    <span className="text-base font-bold text-merapiText-primary w-8 text-center">{paxCount}</span>
                    <button
                      type="button"
                      onClick={() => setPaxCount(paxCount + 1)}
                      className="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold"
                    >
                      +
                    </button>
                  </div>
                  <span className="block text-[11px] text-merapiText-muted mt-1.5">
                    *Maksimal 4 penumpang per armada jeep.
                  </span>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1.5">
                    Jumlah Armada Jeep Dibutuhkan
                  </label>
                  <div className="flex items-center gap-3">
                    <button
                      type="button"
                      onClick={() => setJeepCount(Math.max(minJeeps, jeepCount - 1))}
                      className="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold"
                    >
                      -
                    </button>
                    <span className="text-base font-bold text-wukirsari-primary w-8 text-center">{jeepCount}</span>
                    <button
                      type="button"
                      onClick={() => setJeepCount(jeepCount + 1)}
                      className="w-10 h-10 rounded-xl border border-wukirsari-border bg-slate-50 hover:bg-wukirsari-sage font-bold"
                    >
                      +
                    </button>
                  </div>
                  <span className="block text-[11px] text-emerald-700 font-medium mt-1.5">
                    {jeepCount} Jeep × {formatRupiah(activePackage.price)}
                  </span>
                </div>
              </div>
            </div>

            {/* Step 4: Opsi Kedatangan & Data Kontak */}
            <div className="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs space-y-4">
              <h3 className="text-base font-bold text-merapiText-primary font-display flex items-center gap-2">
                <span className="w-6 h-6 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs flex items-center justify-center font-bold">4</span>
                <span>Opsi Kedatangan & Kontak Pemesan</span>
              </h3>

              {/* Opsi Kedatangan */}
              <div className="grid grid-cols-2 gap-3">
                <button
                  type="button"
                  onClick={() => setArrivalType('self_drive')}
                  className={cn(
                    "p-3 rounded-2xl border text-xs font-semibold text-left transition-all",
                    arrivalType === 'self_drive'
                      ? "border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary"
                      : "border-wukirsari-border text-merapiText-secondary"
                  )}
                >
                  Datang Sendiri ke Basecamp
                </button>
                <button
                  type="button"
                  onClick={() => setArrivalType('pickup')}
                  className={cn(
                    "p-3 rounded-2xl border text-xs font-semibold text-left transition-all",
                    arrivalType === 'pickup'
                      ? "border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary"
                      : "border-wukirsari-border text-merapiText-secondary"
                  )}
                >
                  Layanan Antar-Jemput (Shuttle)
                </button>
              </div>

              {arrivalType === 'pickup' && (
                <div className="p-4 bg-wukirsari-sage/30 rounded-2xl border border-wukirsari-border space-y-3">
                  <label className="block text-xs font-semibold text-merapiText-primary">
                    Pilih Zona Lokasi Penjemputan
                  </label>
                  <select
                    value={pickupZoneId}
                    onChange={(e) => setPickupZoneId(Number(e.target.value))}
                    className="w-full px-3 py-2 rounded-xl border border-wukirsari-border bg-white text-xs"
                  >
                    {pickupZones.slice(1).map((z) => (
                      <option key={z.id} value={z.id}>
                        {z.name} (+{formatRupiah(z.fee)})
                      </option>
                    ))}
                  </select>

                  <input
                    type="text"
                    placeholder="Nama hotel / alamat penjemputan lengkap"
                    value={pickupAddress}
                    onChange={(e) => setPickupAddress(e.target.value)}
                    required={arrivalType === 'pickup'}
                    className="w-full px-3 py-2 rounded-xl border border-wukirsari-border bg-white text-xs"
                  />
                </div>
              )}

              {/* Data Kontak */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1">
                    Nama Lengkap Pemesan *
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="Contoh: Budi Santoso"
                    value={customerName}
                    onChange={(e) => setCustomerName(e.target.value)}
                    className="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-merapiText-primary mb-1">
                    Nomor WhatsApp Aktif *
                  </label>
                  <input
                    type="tel"
                    required
                    placeholder="Contoh: 081234567890"
                    value={customerPhone}
                    onChange={(e) => setCustomerPhone(e.target.value)}
                    className="w-full px-3.5 py-2.5 rounded-xl border border-wukirsari-border text-xs focus:outline-none focus:border-wukirsari-primary"
                  />
                  <span className="block text-[10px] text-merapiText-muted mt-1">
                    *Tiket QR Code akan dikirim ke nomor ini.
                  </span>
                </div>
              </div>

            </div>

          </div>

          {/* Kolom Kanan: Rincian Tagihan & Opsi Pembayaran */}
          <div className="lg:col-span-5 space-y-6">
            
            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-wukirsari-border shadow-sm space-y-6 sticky top-28">
              <h3 className="text-lg font-bold text-merapiText-primary font-display border-b border-wukirsari-border pb-3">
                Ringkasan Tagihan
              </h3>

              {/* Item Rincian */}
              <div className="space-y-3 text-xs sm:text-sm">
                <div className="flex justify-between text-merapiText-secondary">
                  <span>Paket {activePackage.name} ({jeepCount} Jeep)</span>
                  <span className="font-semibold text-merapiText-primary">{formatRupiah(packageSubtotal)}</span>
                </div>

                {arrivalType === 'pickup' && (
                  <div className="flex justify-between text-merapiText-secondary">
                    <span>Layanan Penjemputan</span>
                    <span className="font-semibold text-merapiText-primary">{formatRupiah(pickupFee)}</span>
                  </div>
                )}

                <div className="flex justify-between text-merapiText-secondary">
                  <span>Asuransi & BBM</span>
                  <span className="font-semibold text-emerald-600">Gratis (Termasuk)</span>
                </div>

                <div className="pt-3 border-t border-wukirsari-border flex justify-between items-baseline">
                  <span className="text-sm font-bold text-merapiText-primary">Total Biaya</span>
                  <span className="text-xl font-extrabold text-merapiText-primary font-display">
                    {formatRupiah(totalAmount)}
                  </span>
                </div>
              </div>

              {/* Pilihan Metode Bayar: Full vs DP */}
              <div className="space-y-3 pt-3 border-t border-wukirsari-border">
                <label className="block text-xs font-bold text-merapiText-primary">
                  Pilih Skema Pembayaran
                </label>

                <div className="grid grid-cols-2 gap-2">
                  <button
                    type="button"
                    onClick={() => setPaymentMode('FULL')}
                    className={cn(
                      "p-3 rounded-xl border text-xs font-semibold text-left transition-all",
                      paymentMode === 'FULL'
                        ? "border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary font-bold"
                        : "border-wukirsari-border text-merapiText-secondary"
                    )}
                  >
                    Bayar Lunas (100%)
                    <span className="block text-[10px] font-normal text-merapiText-muted mt-0.5">Langsung beres</span>
                  </button>

                  <button
                    type="button"
                    onClick={() => setPaymentMode('DP')}
                    className={cn(
                      "p-3 rounded-xl border text-xs font-semibold text-left transition-all",
                      paymentMode === 'DP'
                        ? "border-wukirsari-primary bg-wukirsari-sage/40 text-wukirsari-primary font-bold"
                        : "border-wukirsari-border text-merapiText-secondary"
                    )}
                  >
                    Uang Muka (DP 30%)
                    <span className="block text-[10px] font-normal text-merapiText-muted mt-0.5">Pelunasan di basecamp</span>
                  </button>
                </div>
              </div>

              {/* Box Tagihan Sekarang & Sisa */}
              <div className="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border space-y-2">
                <div className="flex justify-between text-xs">
                  <span className="font-semibold text-merapiText-primary">Wajib Dibayar Sekarang:</span>
                  <span className="font-extrabold text-wukirsari-primary text-base font-display">
                    {formatRupiah(paidAmount)}
                  </span>
                </div>
                {paymentMode === 'DP' && (
                  <div className="flex justify-between text-xs text-amber-800 pt-1 border-t border-wukirsari-border">
                    <span>Sisa Bayar di Loket:</span>
                    <span className="font-bold">{formatRupiah(remainingAmount)}</span>
                  </div>
                )}
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                className="w-full py-4 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-wukirsari-primary/25 active:scale-98 transition-all"
              >
                <span>Lanjut ke Pembayaran</span>
                <ArrowRight className="w-4 h-4" />
              </button>

              <div className="flex items-center justify-center gap-2 text-[11px] text-merapiText-muted text-center">
                <ShieldCheck className="w-4 h-4 text-emerald-600 shrink-0" />
                <span>Pembayaran aman terenkripsi via Midtrans Payment Gateway.</span>
              </div>

            </div>

          </div>

        </form>

      </div>
    </div>
  );
}
