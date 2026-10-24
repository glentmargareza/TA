import { Link } from 'react-router-dom';
import { ArrowRight, MapPin, Sparkles, ChevronRight, ChevronLeft } from 'lucide-react';

export default function HeroSection() {
  return (
    <section className="relative min-h-[580px] sm:min-h-[640px] lg:min-h-[720px] flex items-center justify-center overflow-hidden">
      {/* Background Hero Image dengan Overlay Dramatis */}
      <div className="absolute inset-0 z-0">
        <img
          src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=2000&q=80"
          alt="Jeep Merapi Adventure"
          className="w-full h-full object-cover object-center filter brightness-[0.88]"
        />
        {/* Gradients to blend smoothly with nature theme */}
        <div className="absolute inset-0 bg-gradient-to-r from-black/75 via-black/40 to-transparent" />
        <div className="absolute inset-0 bg-gradient-to-t from-wukirsari-dark/80 via-transparent to-black/30" />
      </div>

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full flex flex-col justify-between min-h-[580px] sm:min-h-[640px] lg:min-h-[720px]">
        
        {/* Main Content Box */}
        <div className="max-w-2xl mt-8 sm:mt-12 space-y-6">
          
          {/* Top Pill Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs sm:text-sm font-accent italic">
            <Sparkles className="w-4 h-4 text-sunrise-gold" />
            <span>Petualangan di Lereng Merapi</span>
          </div>

          {/* Headline Utama */}
          <h1 className="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white font-display leading-[1.12] tracking-tight text-shadow-sm">
            Jelajahi Keindahan Alam Merapi Bersama <span className="text-sunrise-gold">Jeep Wukirsari</span>
          </h1>

          {/* Subtitle */}
          <p className="text-white/90 text-sm sm:text-base lg:text-lg leading-relaxed max-w-xl font-normal">
            Rasakan petualangan seru menyusuri jalur lava, menikmati panorama Merapi, dan menciptakan cerita perjalanan yang tak terlupakan.
          </p>

          {/* Dual CTA Buttons */}
          <div className="pt-2 flex flex-wrap items-center gap-4">
            <Link
              to="/booking"
              className="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-wukirsari-primary hover:bg-wukirsari-hover text-white text-sm sm:text-base font-semibold shadow-lg shadow-black/30 hover:scale-[1.02] active:scale-95 transition-all"
            >
              <span>Booking Sekarang</span>
              <ArrowRight className="w-4 h-4" />
            </Link>

            <a
              href="#paket"
              className="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-black/25 hover:bg-white/20 backdrop-blur-md border border-white/40 text-white text-sm sm:text-base font-medium transition-all"
            >
              <span>Jelajahi Paket</span>
            </a>
          </div>
        </div>

        {/* Bottom Bar Hero (Pagination Dots & Location Pin) */}
        <div className="pt-8 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between text-white/80 gap-4 border-t border-white/15">
          {/* Slider Pagination Dummy */}
          <div className="flex items-center gap-3 text-xs tracking-wider">
            <span className="font-bold text-white border-b-2 border-sunrise-gold pb-0.5">01</span>
            <span className="hover:text-white cursor-pointer">02</span>
            <span className="hover:text-white cursor-pointer">03</span>
            <span className="hover:text-white cursor-pointer">04</span>
            <span className="hover:text-white cursor-pointer">05</span>
            <div className="flex items-center gap-1 ml-2">
              <button className="p-1 rounded-full bg-white/10 hover:bg-white/30 transition-colors">
                <ChevronLeft className="w-3.5 h-3.5" />
              </button>
              <button className="p-1 rounded-full bg-white/10 hover:bg-white/30 transition-colors">
                <ChevronRight className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>

          {/* Location Badge */}
          <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/30 backdrop-blur-sm border border-white/20 text-xs text-white">
            <MapPin className="w-3.5 h-3.5 text-sunrise-gold" />
            <span>Wukirsari, Sleman, Yogyakarta</span>
          </div>
        </div>

      </div>
    </section>
  );
}
