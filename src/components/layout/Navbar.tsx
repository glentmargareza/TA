import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Mountain, Menu, X, ArrowRight, Phone } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function Navbar() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const location = useLocation();

  const navLinks = [
    { name: 'Beranda', href: '/' },
    { name: 'Paket Wisata', href: '/#paket' },
    { name: 'Tentang Kami', href: '/#tentang' },
    { name: 'Galeri', href: '/#galeri' },
    { name: 'Kontak', href: '/#kontak' },
    { name: 'Cek Tiket', href: '/cek-tiket' },
  ];

  const isActive = (href: string) => {
    if (href === '/') return location.pathname === '/';
    return location.pathname + location.hash === href;
  };

  return (
    <header className="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-wukirsari-border transition-all">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          
          {/* Logo Brand */}
          <Link to="/" className="flex items-center gap-3 group">
            <div className="w-11 h-11 rounded-2xl bg-wukirsari-primary text-white flex items-center justify-center shadow-md shadow-wukirsari-primary/20 group-hover:scale-105 transition-transform">
              <Mountain className="w-6 h-6" />
            </div>
            <div>
              <span className="block font-display font-extrabold text-lg text-merapiText-primary tracking-tight leading-none">
                JEEP WUKIRSARI
              </span>
              <span className="block text-[11px] font-medium text-merapiText-secondary uppercase tracking-widest mt-0.5">
                Adventure Tour
              </span>
            </div>
          </Link>

          {/* Desktop Nav Links */}
          <nav className="hidden md:flex items-center gap-8">
            {navLinks.map((link) => (
              <a
                key={link.name}
                href={link.href}
                className={cn(
                  "text-sm font-medium transition-colors hover:text-wukirsari-primary py-1 relative",
                  isActive(link.href)
                    ? "text-wukirsari-primary font-semibold"
                    : "text-merapiText-secondary"
                )}
              >
                {link.name}
                {isActive(link.href) && (
                  <span className="absolute bottom-0 left-0 right-0 h-0.5 bg-wukirsari-primary rounded-full" />
                )}
              </a>
            ))}
          </nav>

          {/* Right Desktop CTA */}
          <div className="hidden lg:flex items-center gap-4">
            <a
              href="https://wa.me/6281234567890"
              target="_blank"
              rel="noreferrer"
              className="p-2.5 rounded-full hover:bg-wukirsari-sage text-merapiText-secondary hover:text-wukirsari-primary transition-colors"
              title="Bantuan WhatsApp CS"
            >
              <Phone className="w-5 h-5" />
            </a>

            <Link
              to="/booking"
              className="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-wukirsari-primary text-white text-sm font-semibold hover:bg-wukirsari-hover transition-all shadow-sm shadow-wukirsari-primary/20 hover:scale-[1.02]"
            >
              <span>Booking Sekarang</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>

          {/* Mobile Menu Button */}
          <div className="flex items-center gap-2 md:hidden">
            <Link
              to="/booking"
              className="px-4 py-2 rounded-full bg-wukirsari-primary text-white text-xs font-semibold"
            >
              Booking
            </Link>
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-2 rounded-xl text-merapiText-primary hover:bg-wukirsari-sage transition-colors"
              aria-label="Toggle Menu"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="md:hidden bg-white border-b border-wukirsari-border px-4 pt-2 pb-6 space-y-3 shadow-lg animate-in slide-in-from-top-2">
          {navLinks.map((link) => (
            <a
              key={link.name}
              href={link.href}
              onClick={() => setMobileMenuOpen(false)}
              className="block px-3 py-2.5 rounded-xl text-base font-medium text-merapiText-primary hover:bg-wukirsari-sage transition-colors"
            >
              {link.name}
            </a>
          ))}
          <div className="pt-2">
            <Link
              to="/booking"
              onClick={() => setMobileMenuOpen(false)}
              className="w-full py-3 rounded-full bg-wukirsari-primary text-white text-sm font-semibold flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20"
            >
              <span>Booking Jeep Sekarang</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
