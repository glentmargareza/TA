import { useState } from 'react';
import { Compass, Sparkles, Mountain, ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function App() {
  const [clicked, setClicked] = useState(false);

  return (
    <div className="min-h-screen bg-wukirsari-cream text-merapiText-secondary flex flex-col items-center justify-center p-6">
      <div className="max-w-xl w-full bg-white rounded-3xl shadow-sm border border-wukirsari-border p-8 text-center space-y-6">
        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-wukirsari-sage text-wukirsari-primary text-xs font-semibold tracking-wide">
          <Mountain className="w-4 h-4" />
          <span>Fase 0 Berhasil Diinisialisasi</span>
        </div>

        <h1 className="text-3xl sm:text-4xl font-extrabold text-merapiText-primary font-display leading-tight">
          Jeep Wukirsari Adventure Tour
        </h1>

        <p className="text-merapiText-secondary text-sm sm:text-base leading-relaxed">
          Pondasi Vite + React + TypeScript + Tailwind CSS berhasil dikonfigurasi dengan palet resmi Wukirsari, path alias <code className="bg-wukirsari-sage px-1.5 py-0.5 rounded text-wukirsari-primary font-mono text-xs">@/*</code>, dan pustaka pendukung.
        </p>

        <div className="grid grid-cols-2 gap-3 pt-2">
          <div className="p-3 bg-wukirsari-sage/40 rounded-2xl border border-wukirsari-border text-left">
            <span className="text-xs text-merapiText-muted font-medium">Primary Brand</span>
            <div className="flex items-center gap-2 mt-1">
              <span className="w-4 h-4 rounded-full bg-wukirsari-primary inline-block"></span>
              <span className="text-xs font-semibold text-merapiText-primary">#1E3F20 Deep Forest</span>
            </div>
          </div>

          <div className="p-3 bg-wukirsari-sage/40 rounded-2xl border border-wukirsari-border text-left">
            <span className="text-xs text-merapiText-muted font-medium">Accent Glow</span>
            <div className="flex items-center gap-2 mt-1">
              <span className="w-4 h-4 rounded-full bg-sunrise-gold inline-block"></span>
              <span className="text-xs font-semibold text-merapiText-primary">#F59E0B Sunrise</span>
            </div>
          </div>
        </div>

        <div className="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
          <button
            onClick={() => setClicked(!clicked)}
            className={cn(
              "w-full sm:w-auto px-6 py-2.5 rounded-full font-medium text-sm flex items-center justify-center gap-2 transition-all shadow-sm",
              clicked
                ? "bg-sunrise-gold text-white"
                : "bg-wukirsari-primary hover:bg-wukirsari-hover text-white"
            )}
          >
            <Compass className="w-4 h-4" />
            <span>{clicked ? "Status Aktif & Siap Lanjut!" : "Uji Interaksi Tombol"}</span>
            <ArrowRight className="w-4 h-4" />
          </button>
        </div>

        {clicked && (
          <p className="text-xs text-wukirsari-primary font-medium inline-flex items-center gap-1.5 animate-fade-in">
            <Sparkles className="w-3.5 h-3.5 text-sunrise-gold" />
            Konfigurasi & path alias <span className="font-mono">@/lib/utils</span> bekerja dengan sempurna!
          </p>
        )}
      </div>
    </div>
  );
}
