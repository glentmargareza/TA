import { useSearchParams, Link } from 'react-router-dom';
import { formatRupiah } from '@/components/home/PackageSection';
import { CheckCircle2, Download, MessageCircle, MapPin, ArrowLeft, QrCode } from 'lucide-react';

export default function BookingSuccessPage() {
  const [params] = useSearchParams();
  const code = params.get('code') || 'WKR-829103';
  const name = params.get('name') || 'Wisatawan Jeep';
  const pkg = params.get('pkg') || 'Adventure';
  const date = params.get('date') || '2026-10-25';
  const mode = params.get('mode') || 'FULL';
  const paid = Number(params.get('paid')) || 450000;
  const remaining = Number(params.get('remaining')) || 0;

  const handlePrint = () => {
    window.print();
  };

  const whatsappMessage = `Halo Admin Jeep Wukirsari, saya telah memesan tiket online dengan Kode Booking: ${code} atas nama ${name}.`;
  const waUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(whatsappMessage)}`;

  return (
    <div className="py-12 bg-wukirsari-cream min-h-screen">
      <div className="max-w-2xl mx-auto px-4 sm:px-6">
        
        {/* Banner Sukses */}
        <div className="text-center space-y-3 mb-8">
          <div className="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-sm">
            <CheckCircle2 className="w-9 h-9" />
          </div>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
            Pemesanan Berhasil Dikonfirmasi!
          </h1>
          <p className="text-merapiText-secondary text-sm max-w-md mx-auto">
            Terima kasih telah memesan petualangan bersama Jeep Wukirsari. Simpan atau unduh e-tiket di bawah ini untuk ditunjukkan di loket pangkalan.
          </p>
        </div>

        {/* E-Ticket Card Design */}
        <div className="bg-white rounded-3xl border border-wukirsari-border shadow-lg overflow-hidden relative">
          
          {/* Header Tiket Hijau */}
          <div className="bg-wukirsari-dark text-white p-6 sm:p-8 flex items-center justify-between">
            <div>
              <span className="text-[11px] font-medium text-sunrise-gold uppercase tracking-wider block">
                E-TIKET WISATA RESMI
              </span>
              <h2 className="text-xl sm:text-2xl font-extrabold font-display">
                JEEP WUKIRSARI
              </h2>
              <span className="text-xs text-white/70">Kawasan Lereng Gunung Merapi</span>
            </div>

            <div className="text-right">
              <span className="text-xs text-white/60 block">Kode Booking</span>
              <span className="text-lg sm:text-xl font-mono font-bold text-white tracking-wider">
                {code}
              </span>
            </div>
          </div>

          {/* Garis Perforasi / Gigi Tiket */}
          <div className="relative h-6 bg-white flex items-center justify-between px-2">
            <div className="w-4 h-8 rounded-r-full bg-wukirsari-cream -ml-4" />
            <div className="flex-1 border-b-2 border-dashed border-wukirsari-border mx-3" />
            <div className="w-4 h-8 rounded-l-full bg-wukirsari-cream -mr-4" />
          </div>

          {/* Isi Detail Tiket */}
          <div className="p-6 sm:p-8 space-y-6">
            
            <div className="grid grid-cols-2 gap-4 text-xs sm:text-sm">
              <div>
                <span className="text-merapiText-muted block">Nama Pemesan:</span>
                <span className="font-bold text-merapiText-primary text-sm sm:text-base">{name}</span>
              </div>

              <div>
                <span className="text-merapiText-muted block">Paket Wisata:</span>
                <span className="font-bold text-wukirsari-primary text-sm sm:text-base">Paket {pkg}</span>
              </div>

              <div>
                <span className="text-merapiText-muted block">Tanggal Trip:</span>
                <span className="font-bold text-merapiText-primary">{date}</span>
              </div>

              <div>
                <span className="text-merapiText-muted block">Status Pembayaran:</span>
                <span className={`font-bold inline-block px-2.5 py-0.5 rounded-full text-xs mt-0.5 ${
                  mode === 'FULL' 
                    ? 'bg-emerald-100 text-emerald-800' 
                    : 'bg-amber-100 text-amber-800'
                }`}>
                  {mode === 'FULL' ? 'LUNAS (100%)' : 'UANG MUKA (DP 30%)'}
                </span>
              </div>
            </div>

            {/* Kotak Rincian Finansial */}
            <div className="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border flex items-center justify-between text-xs sm:text-sm">
              <div>
                <span className="text-merapiText-secondary block">Jumlah Terbayar</span>
                <span className="font-extrabold text-wukirsari-primary text-base sm:text-lg">{formatRupiah(paid)}</span>
              </div>
              {remaining > 0 && (
                <div className="text-right">
                  <span className="text-amber-800 block">Sisa Bayar di Loket</span>
                  <span className="font-extrabold text-amber-800 text-base sm:text-lg">{formatRupiah(remaining)}</span>
                </div>
              )}
            </div>

            {/* QR Code Dummy / Display */}
            <div className="flex flex-col sm:flex-row items-center justify-between p-4 border border-wukirsari-border rounded-2xl gap-4">
              <div className="flex items-center gap-4">
                <div className="w-20 h-20 bg-slate-100 rounded-xl border border-wukirsari-border flex items-center justify-center p-2">
                  <QrCode className="w-16 h-16 text-wukirsari-primary" />
                </div>
                <div className="text-xs text-merapiText-secondary">
                  <span className="font-bold text-merapiText-primary block text-sm">QR Code Loket Check-in</span>
                  <span>Tunjukkan QR Code ini ke petugas loket saat tiba di Basecamp Wukirsari.</span>
                </div>
              </div>
            </div>

            {/* Titik Kumpul Lokasi */}
            <div className="flex items-start gap-3 text-xs text-merapiText-secondary bg-slate-50 p-3.5 rounded-xl border border-wukirsari-border">
              <MapPin className="w-4 h-4 text-sunrise-gold shrink-0 mt-0.5" />
              <div>
                <span className="font-bold text-merapiText-primary block">Lokasi Basecamp Wukirsari:</span>
                <span>Jl. Cangkringan Km. 18, Wukirsari, Sleman, D.I. Yogyakarta (Tersedia parkir luas & toilet).</span>
              </div>
            </div>

          </div>

          {/* Action Buttons */}
          <div className="p-6 bg-slate-50 border-t border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-3">
            <button
              onClick={handlePrint}
              className="w-full sm:w-auto px-6 py-2.5 rounded-full border border-wukirsari-border bg-white text-xs sm:text-sm font-semibold text-merapiText-primary hover:bg-slate-100 flex items-center justify-center gap-2"
            >
              <Download className="w-4 h-4" />
              <span>Simpan / Cetak Tiket</span>
            </button>

            <a
              href={waUrl}
              target="_blank"
              rel="noreferrer"
              className="w-full sm:w-auto px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 shadow-sm"
            >
              <MessageCircle className="w-4 h-4" />
              <span>Kirim Konfirmasi ke WA CS</span>
            </a>
          </div>

        </div>

        {/* Link Back */}
        <div className="mt-8 text-center">
          <Link
            to="/"
            className="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Kembali ke Halaman Beranda</span>
          </Link>
        </div>

      </div>
    </div>
  );
}
