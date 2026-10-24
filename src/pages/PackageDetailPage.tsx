import { useParams, Link } from 'react-router-dom';
import { defaultPackages, formatRupiah } from '@/components/home/PackageSection';
import { Clock, Users, ArrowRight, ShieldCheck, CheckCircle2, ChevronLeft } from 'lucide-react';

export default function PackageDetailPage() {
  const { slug } = useParams<{ slug: string }>();
  const pkg = defaultPackages.find((p) => p.slug === slug) || defaultPackages[0];

  const includes = [
    'Driver jeep profesional & pemandu lokal',
    'Bahan bakar minyak (BBM) & armada 4x4 prima',
    'Tiket masuk seluruh objek destinasi paket',
    'Asuransi keselamatan perjalanan wisata',
    'Helm pelindung & masker debu lahar',
  ];

  return (
    <div className="py-12 bg-wukirsari-cream min-h-screen">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Breadcrumb Back */}
        <Link
          to="/#paket"
          className="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors mb-6"
        >
          <ChevronLeft className="w-4 h-4" />
          <span>Kembali ke Daftar Paket</span>
        </Link>

        <div className="bg-white rounded-3xl border border-wukirsari-border overflow-hidden shadow-sm">
          {/* Hero Image */}
          <div className="relative aspect-[16/8] sm:aspect-[21/9] overflow-hidden">
            <img
              src={pkg.image}
              alt={pkg.name}
              className="w-full h-full object-cover"
            />
            {pkg.badge && (
              <span className="absolute top-4 left-4 px-4 py-1.5 rounded-full bg-wukirsari-primary text-white text-xs font-semibold shadow-md">
                {pkg.badge}
              </span>
            )}
          </div>

          {/* Body Content */}
          <div className="p-6 sm:p-10 space-y-8">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-wukirsari-border pb-6">
              <div>
                <h1 className="text-2xl sm:text-4xl font-extrabold text-merapiText-primary font-display">
                  Paket {pkg.name}
                </h1>
                <p className="text-merapiText-secondary text-sm mt-1">
                  Jeep Wukirsari Adventure Tour - Lereng Gunung Merapi
                </p>
              </div>

              <div>
                <span className="text-xs text-merapiText-muted block">Tarif Sewa</span>
                <div className="flex items-baseline gap-1">
                  <span className="text-2xl sm:text-3xl font-extrabold text-merapiText-primary font-display">
                    {formatRupiah(pkg.price)}
                  </span>
                  <span className="text-xs text-merapiText-secondary font-medium">/ Jeep</span>
                </div>
              </div>
            </div>

            {/* Overview & Metadata */}
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
              <div className="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                <span className="text-xs text-merapiText-muted block">Estimasi Durasi</span>
                <div className="flex items-center gap-2 mt-1">
                  <Clock className="w-4 h-4 text-wukirsari-primary" />
                  <span className="text-sm font-bold text-merapiText-primary">{pkg.duration}</span>
                </div>
              </div>

              <div className="p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                <span className="text-xs text-merapiText-muted block">Kapasitas Maksimal</span>
                <div className="flex items-center gap-2 mt-1">
                  <Users className="w-4 h-4 text-wukirsari-primary" />
                  <span className="text-sm font-bold text-merapiText-primary">Max {pkg.maxPax} Orang</span>
                </div>
              </div>

              <div className="col-span-2 sm:col-span-1 p-4 rounded-2xl bg-wukirsari-sage/40 border border-wukirsari-border">
                <span className="text-xs text-merapiText-muted block">Asuransi</span>
                <div className="flex items-center gap-2 mt-1">
                  <ShieldCheck className="w-4 h-4 text-wukirsari-primary" />
                  <span className="text-sm font-bold text-merapiText-primary">Termasuk</span>
                </div>
              </div>
            </div>

            {/* Deskripsi */}
            <div className="space-y-3">
              <h3 className="text-lg font-bold text-merapiText-primary font-display">
                Deskripsi Rute Perjalanan
              </h3>
              <p className="text-merapiText-secondary text-sm sm:text-base leading-relaxed">
                {pkg.description} Menyusuri kawasan saksi bisu erupsi Gunung Merapi dengan jalur bebatuan, pasir vulkanik, spot foto pemandangan terbaik, dan aksi susur air di aliran Sungai Kalikuning yang memacu adrenalin namun tetap terjamin keamanannya.
              </p>
            </div>

            {/* Fasilitas Include */}
            <div className="space-y-3">
              <h3 className="text-lg font-bold text-merapiText-primary font-display">
                Fasilitas yang Termasuk (Include)
              </h3>
              <ul className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-merapiText-secondary">
                {includes.map((inc, i) => (
                  <li key={i} className="flex items-center gap-2.5">
                    <CheckCircle2 className="w-4 h-4 text-wukirsari-primary shrink-0" />
                    <span>{inc}</span>
                  </li>
                ))}
              </ul>
            </div>

            {/* CTA Box */}
            <div className="pt-6 border-t border-wukirsari-border flex flex-col sm:flex-row items-center justify-between gap-4">
              <div>
                <span className="text-xs text-merapiText-muted block">Siap berpetualang?</span>
                <span className="text-sm font-semibold text-merapiText-primary">Pemesanan online instan dengan opsi DP atau Bayar Penuh.</span>
              </div>

              <Link
                to={`/booking?package=${pkg.slug}`}
                className="w-full sm:w-auto px-8 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 hover:scale-[1.02] transition-all"
              >
                <span>Pesan Paket Ini Sekarang</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
            </div>

          </div>
        </div>

      </div>
    </div>
  );
}
