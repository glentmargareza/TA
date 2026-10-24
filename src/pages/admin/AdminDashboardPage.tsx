import { useState } from 'react';
import { formatRupiah } from '@/components/home/PackageSection';
import { Car, Users, CheckCircle2, QrCode } from 'lucide-react';

export default function AdminDashboardPage() {
  const [bookings, setBookings] = useState([
    {
      id: 1,
      code: 'WKR-928174',
      customer: 'Andi Pratama',
      phone: '081234567891',
      pkg: 'Adventure',
      tripDate: '2026-10-25',
      slot: '08:00 WIB',
      jeeps: 1,
      pax: 4,
      total: 450000,
      paid: 135000,
      remaining: 315000,
      status: 'DP_PAID',
      driver: 'Pak Slamet Merapi',
      plate: 'AB 1024 WK',
    },
    {
      id: 2,
      code: 'WKR-817293',
      customer: 'Siti Nurhaliza',
      phone: '081234567892',
      pkg: 'Sunrise Tour',
      tripDate: '2026-10-25',
      slot: '04:30 WIB',
      jeeps: 2,
      pax: 8,
      total: 1400000,
      paid: 1400000,
      remaining: 0,
      status: 'FULL_PAID',
      driver: 'Mas Joko Wukirsari',
      plate: 'AB 2048 WK',
    },
    {
      id: 3,
      code: 'WKR-736281',
      customer: 'Rizky Maulana',
      phone: '081234567893',
      pkg: 'Lavature',
      tripDate: '2026-10-25',
      slot: '10:30 WIB',
      jeeps: 1,
      pax: 4,
      total: 550000,
      paid: 165000,
      remaining: 385000,
      status: 'DP_PAID',
      driver: 'Belum Ditugaskan',
      plate: '-',
    },
  ]);

  const handleSettle = (id: number) => {
    setBookings(
      bookings.map((b) =>
        b.id === id ? { ...b, status: 'FULL_PAID', paid: b.total, remaining: 0 } : b
      )
    );
    alert('Pelunasan berhasil dicatat di sistem loket basecamp!');
  };

  return (
    <div className="space-y-8">
      
      {/* 3 Overview Stat Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
              Omzet Terverifikasi Hari Ini
            </span>
            <span className="text-2xl font-extrabold text-slate-800 font-display block mt-1">
              Rp 1.700.000
            </span>
            <span className="text-xs text-emerald-600 font-medium">3 Transaksi Berhasil</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <CheckCircle2 className="w-6 h-6" />
          </div>
        </div>

        <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
              Armada Jeep Siap Berangkat
            </span>
            <span className="text-2xl font-extrabold text-slate-800 font-display block mt-1">
              18 / 20 Jeep
            </span>
            <span className="text-xs text-slate-500">2 Sedang Pemeliharaan</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <Car className="w-6 h-6" />
          </div>
        </div>

        <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
              Menunggu Pelunasan Loket
            </span>
            <span className="text-2xl font-extrabold text-amber-600 font-display block mt-1">
              Rp 700.000
            </span>
            <span className="text-xs text-amber-700 font-medium">2 Booking Berstatus DP</span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <Users className="w-6 h-6" />
          </div>
        </div>
      </div>

      {/* Tabel Reservasi Terkini */}
      <div className="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div className="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Daftar Reservasi & Penugasan Driver
            </h3>
            <p className="text-xs text-slate-500">
              Kelola penugasan mobil paguyuban dan validasi pelunasan uang muka di loket basecamp.
            </p>
          </div>

          <div className="flex items-center gap-2">
            <button
              onClick={() => alert('Fitur scanner kamera aktif di browser!')}
              className="px-4 py-2 rounded-xl bg-wukirsari-primary text-white text-xs font-semibold flex items-center gap-1.5 shadow-sm"
            >
              <QrCode className="w-4 h-4" />
              <span>Buka Scanner Kamera</span>
            </button>
          </div>
        </div>

        {/* Tabel Data */}
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse text-xs">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                <th className="py-3 px-4">Kode Booking</th>
                <th className="py-3 px-4">Pemesan</th>
                <th className="py-3 px-4">Paket & Jam</th>
                <th className="py-3 px-4">Armada & Pax</th>
                <th className="py-3 px-4">Status Bayar</th>
                <th className="py-3 px-4">Driver & Plat</th>
                <th className="py-3 px-4 text-center">Aksi Loket</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {bookings.map((b) => (
                <tr key={b.id} className="hover:bg-slate-50 transition-colors">
                  <td className="py-3.5 px-4 font-mono font-bold text-slate-700">
                    {b.code}
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="font-semibold text-slate-800 block">{b.customer}</span>
                    <span className="text-slate-400">{b.phone}</span>
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="font-semibold text-slate-800 block">{b.pkg}</span>
                    <span className="text-slate-500">{b.slot}</span>
                  </td>
                  <td className="py-3.5 px-4">
                    <span>{b.jeeps} Jeep ({b.pax} Org)</span>
                  </td>
                  <td className="py-3.5 px-4">
                    {b.status === 'FULL_PAID' ? (
                      <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                        LUNAS 100%
                      </span>
                    ) : (
                      <div>
                        <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                          DP TERBAYAR
                        </span>
                        <span className="block text-[10px] text-red-600 font-semibold mt-0.5">
                          Sisa: {formatRupiah(b.remaining)}
                        </span>
                      </div>
                    )}
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="font-medium text-slate-800 block">{b.driver}</span>
                    <span className="text-slate-400 font-mono">{b.plate}</span>
                  </td>
                  <td className="py-3.5 px-4 text-center">
                    {b.status === 'DP_PAID' ? (
                      <button
                        onClick={() => handleSettle(b.id)}
                        className="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] shadow-xs transition-colors"
                      >
                        Terima Pelunasan
                      </button>
                    ) : (
                      <span className="text-emerald-700 font-semibold text-[11px]">Selesai</span>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

    </div>
  );
}
