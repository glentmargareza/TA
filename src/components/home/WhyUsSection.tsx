import { ArrowRight, ShieldCheck, HeartHandshake, Compass, Star } from 'lucide-react';

export default function WhyUsSection() {
  const points = [
    {
      title: 'Harga Transparan',
      desc: 'Tanpa biaya tersembunyi',
      icon: ShieldCheck,
    },
    {
      title: 'Pelayanan Ramah',
      desc: 'Siap membantu 24 jam',
      icon: HeartHandshake,
    },
    {
      title: 'Jalur Aman',
      desc: 'Dipilih sesuai kondisi',
      icon: Compass,
    },
    {
      title: 'Pengalaman Terbaik',
      desc: 'Banyak review positif',
      icon: Star,
    },
  ];

  return (
    <section id="tentang" className="py-16 sm:py-20 bg-gradient-to-b from-wukirsari-cream to-wukirsari-sage/40 relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          
          {/* Kotak Hijau Sisi Kiri */}
          <div className="lg:col-span-5 bg-wukirsari-dark text-white p-8 sm:p-10 rounded-3xl shadow-xl space-y-6 relative overflow-hidden">
            {/* Background Accent Subtle Glow */}
            <div className="absolute top-0 right-0 w-48 h-48 bg-wukirsari-primary/30 rounded-full blur-3xl pointer-events-none" />

            <div>
              <span className="text-sm font-accent italic text-wukirsari-sage block mb-1">
                Mengapa Memilih
              </span>
              <h2 className="text-2xl sm:text-3xl font-extrabold text-white font-display">
                Jeep Wukirsari?
              </h2>
            </div>

            <p className="text-white/80 text-sm sm:text-base leading-relaxed">
              Kami berkomitmen memberikan pengalaman terbaik untuk setiap perjalanan Anda. Dengan pelayanan profesional, fasilitas lengkap dan rute yang aman, liburan Anda akan menjadi lebih berkesan.
            </p>

            <div className="pt-2">
              <a
                href="#kontak"
                className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-wukirsari-dark text-sm font-semibold hover:bg-wukirsari-sage transition-all shadow-md active:scale-95"
              >
                <span>Tentang Kami</span>
                <ArrowRight className="w-4 h-4" />
              </a>
            </div>
          </div>

          {/* 4 Kartu Fitur Sisi Kanan (Grid 2x2) */}
          <div className="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            {points.map((pt) => {
              const Icon = pt.icon;
              return (
                <div
                  key={pt.title}
                  className="bg-white/80 backdrop-blur-sm p-6 rounded-2xl border border-wukirsari-border shadow-xs hover:shadow-md hover:bg-white transition-all flex flex-col justify-between space-y-4"
                >
                  <div className="w-12 h-12 rounded-2xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0 border border-wukirsari-primary/10">
                    <Icon className="w-6 h-6" />
                  </div>
                  <div>
                    <h4 className="text-base font-bold text-merapiText-primary font-display">
                      {pt.title}
                    </h4>
                    <p className="text-xs sm:text-sm text-merapiText-secondary mt-1">
                      {pt.desc}
                    </p>
                  </div>
                </div>
              );
            })}
          </div>

        </div>

      </div>
    </section>
  );
}
