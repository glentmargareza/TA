import React, { useState } from 'react';
import { Search, MapPin, AlertCircle } from 'lucide-react';
import { formatRupiah } from '@/components/home/PackageSection';

export default function CheckTicketPage() {
  const [searchCode, setSearchCode] = useState('');
  const [ticketData, setTicketData] = useState<any | null>(null);
  const [hasSearched, setHasSearched] = useState(false);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setHasSearched(true);

    if (searchCode.trim().toUpperCase() === 'DEMO' || searchCode.trim().length >= 4) {
      setTicketData({
        code: searchCode.toUpperCase(),
        name: 'Budi Santoso',
        pkg: 'Adventure',
        tripDate: '2026-10-26',
        slot: '08:00 WIB',
        pax: 4,
        jeepCount: 1,
        mode: 'DP',
        paid: 135000,
        remaining: 315000,
        status: 'DP_PAID',
      });
    } else {
      setTicketData(null);
    }
  };

  return (
    <div className="py-12 bg-wukirsari-cream min-h-screen">
      <div className="max-w-2xl mx-auto px-4 sm:px-6">
        
        <div className="text-center space-y-2 mb-8">
          <span className="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block">
            PELACAKAN TIKET ONLINE
          </span>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
            Cek Status Pemesanan Jeep
          </h1>
          <p className="text-merapiText-secondary text-sm">
            Masukkan kode booking (contoh: <code>WKR-123456</code>) atau nomor WhatsApp pemesan untuk melihat status dan e-tiket Anda.
          </p>
        </div>

        {/* Input Search Form */}
        <form onSubmit={handleSearch} className="flex gap-2 mb-8">
          <input
            type="text"
            required
            placeholder="Masukkan Kode Booking / No WhatsApp..."
            value={searchCode}
            onChange={(e) => setSearchCode(e.target.value)}
            className="flex-1 px-4 py-3 rounded-full border border-wukirsari-border bg-white text-sm focus:outline-none focus:border-wukirsari-primary shadow-xs"
          />
          <button
            type="submit"
            className="px-6 py-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm font-semibold flex items-center gap-2 shadow-sm"
          >
            <Search className="w-4 h-4" />
            <span>Cari Tiket</span>
          </button>
        </form>

        {/* Hasil Pencarian */}
        {hasSearched && ticketData && (
          <div className="bg-white rounded-3xl border border-wukirsari-border p-6 sm:p-8 shadow-sm space-y-6 animate-in fade-in duration-300">
            <div className="flex items-center justify-between border-b border-wukirsari-border pb-4">
              <div>
                <span className="text-xs text-merapiText-muted block">Kode Reservasi</span>
                <span className="text-lg font-mono font-bold text-wukirsari-primary">{ticketData.code}</span>
              </div>
              <span className="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                UANG MUKA DITERIMA (DP)
              </span>
            </div>

            <div className="grid grid-cols-2 gap-4 text-xs sm:text-sm">
              <div>
                <span className="text-merapiText-muted block">Nama Pemesan:</span>
                <span className="font-bold text-merapiText-primary">{ticketData.name}</span>
              </div>
              <div>
                <span className="text-merapiText-muted block">Paket Wisata:</span>
                <span className="font-bold text-merapiText-primary">Paket {ticketData.pkg}</span>
              </div>
              <div>
                <span className="text-merapiText-muted block">Tanggal & Jam:</span>
                <span className="font-bold text-merapiText-primary">{ticketData.tripDate} ({ticketData.slot})</span>
              </div>
              <div>
                <span className="text-merapiText-muted block">Peserta & Armada:</span>
                <span className="font-bold text-merapiText-primary">{ticketData.pax} Pax ({ticketData.jeepCount} Jeep)</span>
              </div>
            </div>

            <div className="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border flex justify-between items-center text-xs sm:text-sm">
              <div>
                <span className="text-merapiText-secondary block">Terbayar (DP 30%)</span>
                <span className="font-bold text-wukirsari-primary">{formatRupiah(ticketData.paid)}</span>
              </div>
              <div className="text-right">
                <span className="text-amber-800 block">Sisa Bayar di Loket</span>
                <span className="font-extrabold text-amber-800 text-base">{formatRupiah(ticketData.remaining)}</span>
              </div>
            </div>

            <div className="flex items-center gap-2 text-xs text-merapiText-secondary bg-slate-50 p-3 rounded-xl border border-wukirsari-border">
              <MapPin className="w-4 h-4 text-sunrise-gold shrink-0" />
              <span>Silakan datang 15 menit sebelum jadwal ke Basecamp Jeep Wukirsari Cangkringan.</span>
            </div>
          </div>
        )}

        {hasSearched && !ticketData && (
          <div className="bg-white rounded-3xl border border-wukirsari-border p-8 text-center space-y-3 shadow-xs">
            <AlertCircle className="w-10 h-10 text-amber-500 mx-auto" />
            <h3 className="text-base font-bold text-merapiText-primary">Tiket Tidak Ditemukan</h3>
            <p className="text-xs sm:text-sm text-merapiText-secondary max-w-sm mx-auto">
              Pastikan kode booking atau nomor WhatsApp yang Anda masukkan sudah benar dan telah menyelesaikan pembayaran.
            </p>
          </div>
        )}

      </div>
    </div>
  );
}
