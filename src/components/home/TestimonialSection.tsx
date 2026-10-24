import { Star, ChevronLeft, ChevronRight } from 'lucide-react';

export default function TestimonialSection() {
  const reviews = [
    {
      name: 'Andi Pratama',
      city: 'Yogyakarta',
      rating: 5,
      avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
      comment: 'Pengalaman yang luar biasa! Driver-nya ramah dan sangat berpengalaman. Pemandangannya keren banget!',
    },
    {
      name: 'Siti Nurhaliza',
      city: 'Sleman',
      rating: 5,
      avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
      comment: 'Sunrise di Merapi benar-benar magical. Timnya profesional dan semua sesuai dengan yang dijanjikan.',
    },
    {
      name: 'Rizky Maulana',
      city: 'Bandung',
      rating: 5,
      avatar: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?auto=format&fit=crop&w=150&q=80',
      comment: 'Booking-nya mudah, adminnya responsif, dan semuanya sesuai dengan yang dijanjikan.',
    },
  ];

  return (
    <section className="py-16 sm:py-20 bg-gradient-to-b from-wukirsari-cream to-white border-t border-wukirsari-border">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header Section */}
        <div className="flex items-end justify-between mb-12">
          <div>
            <span className="text-xs font-bold uppercase tracking-wider text-wukirsari-primary font-display block mb-1">
              TESTIMONI
            </span>
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-merapiText-primary font-display">
              Apa Kata Mereka?
            </h2>
            <p className="text-merapiText-secondary text-sm sm:text-base mt-2">
              Pengalaman nyata dari pelanggan yang sudah merasakan keseruannya.
            </p>
          </div>

          <div className="hidden sm:flex items-center gap-2">
            <button className="p-2.5 rounded-full border border-wukirsari-border bg-white hover:bg-wukirsari-sage text-merapiText-primary transition-colors">
              <ChevronLeft className="w-4 h-4" />
            </button>
            <button className="p-2.5 rounded-full border border-wukirsari-border bg-white hover:bg-wukirsari-sage text-merapiText-primary transition-colors">
              <ChevronRight className="w-4 h-4" />
            </button>
          </div>
        </div>

        {/* Reviews 3 Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {reviews.map((rev) => (
            <div
              key={rev.name}
              className="bg-white p-6 rounded-3xl border border-wukirsari-border shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-4"
            >
              <div className="space-y-4">
                {/* Author Info */}
                <div className="flex items-center gap-3.5">
                  <img
                    src={rev.avatar}
                    alt={rev.name}
                    className="w-11 h-11 rounded-full object-cover border-2 border-wukirsari-border"
                  />
                  <div>
                    <h4 className="text-sm font-bold text-merapiText-primary font-display">
                      {rev.name}
                    </h4>
                    <span className="text-xs text-merapiText-muted">
                      {rev.city}
                    </span>
                  </div>
                </div>

                {/* Comment Quote */}
                <p className="text-xs sm:text-sm text-merapiText-secondary italic leading-relaxed">
                  "{rev.comment}"
                </p>
              </div>

              {/* 5 Stars */}
              <div className="flex items-center gap-1 text-sunrise-gold pt-2">
                {[...Array(rev.rating)].map((_, i) => (
                  <Star key={i} className="w-4 h-4 fill-sunrise-gold text-sunrise-gold" />
                ))}
              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
}
