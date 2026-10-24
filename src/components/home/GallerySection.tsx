import { ArrowRight } from 'lucide-react';

export default function GallerySection() {
  const images = [
    {
      title: 'Trek Pasir Kali Gendol',
      url: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=600&q=80',
    },
    {
      title: 'Sunrise Fajar Merapi',
      url: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',
    },
    {
      title: 'Bunker Kaliadem',
      url: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=600&q=80',
    },
    {
      title: 'Manuver Air Kalikuning',
      url: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80',
    },
    {
      title: 'Lembah Hijau Lereng Merapi',
      url: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=600&q=80',
    },
  ];

  return (
    <section id="galeri" className="py-16 sm:py-20 bg-wukirsari-cream">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header Section */}
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
          <div>
            <span className="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">
              GALERI
            </span>
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">
              Jelajahi Momen Indah Bersama Kami
            </h2>
            <p className="text-merapiText-secondary text-sm sm:text-base mt-2 max-w-xl">
              Kumpulan foto perjalanan seru wisatawan di berbagai rute eksotis lereng Gunung Merapi.
            </p>
          </div>

          <a
            href="#galeri"
            className="inline-flex items-center gap-1.5 text-sm font-semibold text-wukirsari-primary hover:text-wukirsari-hover transition-colors"
          >
            <span>Lihat Semua Galeri</span>
            <ArrowRight className="w-4 h-4" />
          </a>
        </div>

        {/* 5 Foto Grid */}
        <div className="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4">
          {images.map((item, idx) => (
            <div
              key={idx}
              className={`rounded-2xl overflow-hidden shadow-xs border border-wukirsari-border group relative aspect-[4/3] ${
                idx === 0 ? 'col-span-2 md:col-span-1' : ''
              }`}
            >
              <img
                src={item.url}
                alt={item.title}
                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                <span className="text-xs font-medium text-white line-clamp-1">
                  {item.title}
                </span>
              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
}
