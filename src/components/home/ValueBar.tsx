import { ShieldCheck, MapPin, Compass, Car } from 'lucide-react';

export default function ValueBar() {
  const values = [
    {
      title: 'Jeep 4x4',
      desc: 'Siap di segala medan',
      icon: Car,
    },
    {
      title: 'Driver Berpengalaman',
      desc: 'Pemandu profesional',
      icon: ShieldCheck,
    },
    {
      title: 'Fasilitas Lengkap',
      desc: 'Aman dan nyaman',
      icon: Compass,
    },
    {
      title: 'Lokasi Strategis',
      desc: 'Dekat Gunung Merapi',
      icon: MapPin,
    },
  ];

  return (
    <section className="bg-wukirsari-sage/60 py-6 border-b border-wukirsari-border">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
          {values.map((item) => {
            const Icon = item.icon;
            return (
              <div
                key={item.title}
                className="flex items-center gap-3.5 p-3 rounded-2xl bg-white/70 backdrop-blur-sm border border-wukirsari-border/70 hover:bg-white transition-all shadow-xs"
              >
                <div className="w-11 h-11 rounded-xl bg-wukirsari-sage text-wukirsari-primary flex items-center justify-center shrink-0 border border-wukirsari-primary/10">
                  <Icon className="w-5 h-5" />
                </div>
                <div className="min-w-0">
                  <h4 className="text-xs sm:text-sm font-bold text-merapiText-primary truncate">
                    {item.title}
                  </h4>
                  <p className="text-[11px] sm:text-xs text-merapiText-secondary truncate">
                    {item.desc}
                  </p>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
