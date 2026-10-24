import { Link } from 'react-router-dom';
import { Clock, Users, ArrowRight } from 'lucide-react';

export interface PackageItem {
  id: number;
  name: string;
  slug: string;
  description: string;
  price: number;
  duration: string;
  maxPax: number;
  badge?: string;
  image: string;
}

export const defaultPackages: PackageItem[] = [
  {
    id: 1,
    name: 'Adventure',
    slug: 'adventure',
    description: 'Jelajahi jalur lava dan pemandangan menakjubkan Gunung Merapi dengan sensasi petualangan yang seru.',
    price: 450000,
    duration: '1,5 - 2 jam',
    maxPax: 4,
    badge: 'Paling Populer',
    image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 2,
    name: 'Lavature',
    slug: 'lavature',
    description: 'Nikmati keindahan alam Merapi dengan jalur yang lebih menantang dan pemandangan yang memukau.',
    price: 550000,
    duration: '2 - 2,5 jam',
    maxPax: 4,
    badge: 'Paket Sedang',
    image: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 3,
    name: 'Sunrise Tour',
    slug: 'sunrise-tour',
    description: 'Saksikan matahari terbit di atas Merapi dengan suasana yang sejuk dan menenangkan.',
    price: 700000,
    duration: '3 - 4 jam',
    maxPax: 4,
    badge: 'Sunrise Spesial',
    image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
  },
];

export const formatRupiah = (num: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(num);
};

export default function PackageSection() {
  return (
    <section id="paket" className="py-16 sm:py-20 bg-wukirsari-cream">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div>
            <span className="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">
              PAKET WISATA
            </span>
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">
              Paket Wisata Populer
            </h2>
            <p className="text-merapiText-secondary text-sm sm:text-base mt-2 max-w-xl">
              Pilihan terbaik untuk pengalaman tak terlupakan di setiap perjalanan lereng Merapi.
            </p>
          </div>

          <a
            href="#paket"
            className="inline-flex items-center gap-1.5 text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors"
          >
            <span>Lihat Semua</span>
            <ArrowRight className="w-4 h-4" />
          </a>
        </div>

        {/* 3 Packages Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {defaultPackages.map((pkg) => (
            <div
              key={pkg.id}
              className="bg-white rounded-3xl overflow-hidden border border-wukirsari-border shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group"
            >
              {/* Gambar Card dengan Badge */}
              <div className="relative aspect-[16/10] overflow-hidden bg-slate-100">
                <img
                  src={pkg.image}
                  alt={pkg.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                {pkg.badge && (
                  <span className="absolute top-4 left-4 px-3.5 py-1 rounded-full bg-wukirsari-primary text-white text-xs font-semibold shadow-md">
                    {pkg.badge}
                  </span>
                )}
              </div>

              {/* Konten Card */}
              <div className="p-6 flex-1 flex flex-col justify-between space-y-4">
                <div>
                  <h3 className="text-xl font-bold text-merapiText-primary font-display group-hover:text-wukirsari-primary transition-colors">
                    {pkg.name}
                  </h3>
                  <p className="text-merapiText-secondary text-xs sm:text-sm mt-2 line-clamp-2 leading-relaxed">
                    {pkg.description}
                  </p>
                </div>

                {/* Metadata Jam & Kapasitas */}
                <div className="pt-2 flex items-center gap-5 text-xs text-merapiText-secondary border-t border-wukirsari-border/70">
                  <div className="flex items-center gap-1.5">
                    <Clock className="w-4 h-4 text-wukirsari-primary" />
                    <span>{pkg.duration}</span>
                  </div>
                  <div className="flex items-center gap-1.5">
                    <Users className="w-4 h-4 text-wukirsari-primary" />
                    <span>Max {pkg.maxPax} orang</span>
                  </div>
                </div>

                {/* Harga & Tombol Aksi */}
                <div className="pt-3 border-t border-wukirsari-border space-y-4">
                  <div className="flex items-baseline gap-1">
                    <span className="text-xl sm:text-2xl font-extrabold text-merapiText-primary font-display">
                      {formatRupiah(pkg.price)}
                    </span>
                    <span className="text-xs text-merapiText-secondary font-medium">/ Jeep</span>
                  </div>

                  <div className="grid grid-cols-2 gap-2">
                    <Link
                      to={`/packages/${pkg.slug}`}
                      className="py-2.5 px-3 rounded-full border border-wukirsari-border text-merapiText-primary hover:border-wukirsari-primary hover:text-wukirsari-primary text-xs font-semibold text-center transition-all"
                    >
                      Lihat Detail →
                    </Link>
                    <Link
                      to={`/booking?package=${pkg.slug}`}
                      className="py-2.5 px-3 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-xs font-semibold text-center transition-all shadow-sm shadow-wukirsari-primary/20"
                    >
                      Booking Sekarang
                    </Link>
                  </div>
                </div>

              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
}
