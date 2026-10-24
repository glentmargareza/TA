import { Outlet, Link, useLocation } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
import { ArrowRight } from 'lucide-react';

export default function MainLayout() {
  const location = useLocation();
  const isBookingPage = location.pathname.startsWith('/booking');

  return (
    <div className="min-h-screen bg-wukirsari-cream flex flex-col font-sans selection:bg-wukirsari-sage selection:text-wukirsari-primary">
      {/* Header Navigasi */}
      <Navbar />

      {/* Konten Halaman */}
      <main className="flex-1">
        <Outlet />
      </main>

      {/* Footer Utama */}
      <Footer />

      {/* Sticky Bottom Action Bar (Khusus Layar Ponsel & jika bukan di halaman booking) */}
      {!isBookingPage && (
        <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-wukirsari-border px-4 py-3 shadow-lg flex items-center justify-between gap-3">
          <div>
            <span className="block text-[11px] font-medium text-merapiText-muted">Mulai dari</span>
            <span className="text-base font-bold text-merapiText-primary leading-tight">Rp 450.000<span className="text-xs font-normal text-merapiText-secondary">/jeep</span></span>
          </div>
          <Link
            to="/booking"
            className="flex-1 max-w-[220px] py-2.5 px-4 rounded-full bg-wukirsari-primary text-white text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 shadow-md shadow-wukirsari-primary/20 active:scale-95 transition-all"
          >
            <span>Booking Sekarang</span>
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      )}
    </div>
  );
}
