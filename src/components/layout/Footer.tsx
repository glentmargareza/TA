import { Link } from 'react-router-dom';
import { Mountain, Phone, Mail, MapPin, MessageCircle, Globe, Share2 } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-wukirsari-dark text-white/80 pt-16 pb-12 border-t border-wukirsari-primary/30">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">
          
          {/* Kolom 1: Brand & Tagline */}
          <div className="space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-xl bg-wukirsari-primary text-white flex items-center justify-center border border-white/20">
                <Mountain className="w-5 h-5" />
              </div>
              <div>
                <span className="block font-display font-extrabold text-lg text-white tracking-tight leading-none">
                  JEEP WUKIRSARI
                </span>
                <span className="block text-[10px] font-medium text-wukirsari-sage uppercase tracking-widest mt-0.5">
                  Adventure Tour
                </span>
              </div>
            </div>
            <p className="text-sm text-white/70 leading-relaxed font-accent italic">
              "Petualangan hari ini, cerita untuk selamanya."
            </p>
            <p className="text-xs text-white/60 leading-relaxed">
              Jelajahi jalur lahar dingin, bebatuan vulkanik, dan keindahan alam Gunung Merapi bersama pemandu profesional dan armada 4x4 tangguh.
            </p>
          </div>

          {/* Kolom 2: Navigasi Cepat */}
          <div>
            <h4 className="text-white font-semibold text-sm tracking-wider uppercase mb-4">
              Navigasi
            </h4>
            <ul className="space-y-2.5 text-sm">
              <li>
                <Link to="/" className="hover:text-white transition-colors">Beranda</Link>
              </li>
              <li>
                <a href="/#paket" className="hover:text-white transition-colors">Paket Wisata</a>
              </li>
              <li>
                <a href="/#tentang" className="hover:text-white transition-colors">Tentang Kami</a>
              </li>
              <li>
                <a href="/#galeri" className="hover:text-white transition-colors">Galeri Dokumentasi</a>
              </li>
              <li>
                <Link to="/cek-tiket" className="hover:text-white transition-colors">Cek Status Tiket</Link>
              </li>
            </ul>
          </div>

          {/* Kolom 3: Kontak Kami */}
          <div>
            <h4 className="text-white font-semibold text-sm tracking-wider uppercase mb-4">
              Kontak Kami
            </h4>
            <ul className="space-y-3 text-sm">
              <li className="flex items-start gap-3">
                <Phone className="w-4 h-4 text-sunrise-gold shrink-0 mt-1" />
                <a href="tel:+6281234567890" className="hover:text-white transition-colors">
                  +62 812-3456-7890
                </a>
              </li>
              <li className="flex items-start gap-3">
                <Mail className="w-4 h-4 text-sunrise-gold shrink-0 mt-1" />
                <a href="mailto:jeepwukirsari@gmail.com" className="hover:text-white transition-colors">
                  jeepwukirsari@gmail.com
                </a>
              </li>
              <li className="flex items-start gap-3">
                <MapPin className="w-4 h-4 text-sunrise-gold shrink-0 mt-1" />
                <span>Basecamp Jeep Wukirsari, Kapanewon Cangkringan, Sleman, D.I. Yogyakarta</span>
              </li>
            </ul>
          </div>

          {/* Kolom 4: Media Sosial & Legal */}
          <div>
            <h4 className="text-white font-semibold text-sm tracking-wider uppercase mb-4">
              Ikuti Kami
            </h4>
            <div className="flex items-center gap-3 mb-6">
              <a
                href="https://instagram.com"
                target="_blank"
                rel="noreferrer"
                className="w-10 h-10 rounded-full bg-white/10 hover:bg-wukirsari-primary flex items-center justify-center text-white transition-all hover:scale-105"
                title="Instagram Resmi"
              >
                <Globe className="w-4 h-4" />
              </a>
              <a
                href="https://wa.me/6281234567890"
                target="_blank"
                rel="noreferrer"
                className="w-10 h-10 rounded-full bg-white/10 hover:bg-wukirsari-primary flex items-center justify-center text-white transition-all hover:scale-105"
                title="WhatsApp Resmi"
              >
                <MessageCircle className="w-4 h-4" />
              </a>
              <a
                href="#share"
                className="w-10 h-10 rounded-full bg-white/10 hover:bg-wukirsari-primary flex items-center justify-center text-white transition-all hover:scale-105"
                title="Bagikan Website"
              >
                <Share2 className="w-4 h-4" />
              </a>
            </div>

            <div className="p-3 bg-white/5 rounded-2xl border border-white/10 text-xs text-white/60">
              <span className="block font-medium text-white mb-1">Pangkalan Siaga 24 Jam</span>
              <span>Layanan booking online instan dengan konfirmasi e-tiket WhatsApp langsung.</span>
            </div>
          </div>

        </div>

        {/* Baris Hak Cipta Bawah */}
        <div className="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
          <p>© 2026 Jeep Wukirsari. Semua hak dilindungi undang-undang.</p>
          <div className="flex items-center gap-6">
            <a href="#" className="hover:text-white transition-colors">Syarat & Ketentuan</a>
            <a href="#" className="hover:text-white transition-colors">Kebijakan Privasi</a>
            <Link to="/admin" className="hover:text-sunrise-gold transition-colors font-medium">Portal Admin</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
