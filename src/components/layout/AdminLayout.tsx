import { Outlet, Link, useLocation } from 'react-router-dom';
import { Mountain, LayoutDashboard, Calendar, QrCode, ArrowLeft } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function AdminLayout() {
  const location = useLocation();

  const menuItems = [
    { name: 'Ringkasan & Omzet', href: '/admin', icon: LayoutDashboard },
    { name: 'Manajemen Reservasi', href: '/admin/bookings', icon: Calendar },
    { name: 'Scanner QR Loket', href: '/admin/scan', icon: QrCode },
  ];

  return (
    <div className="min-h-screen bg-slate-100 flex flex-col md:flex-row font-sans">
      {/* Sidebar Admin */}
      <aside className="w-full md:w-64 bg-wukirsari-dark text-white p-6 flex flex-col justify-between shrink-0">
        <div>
          {/* Brand Admin */}
          <div className="flex items-center gap-3 pb-6 border-b border-white/10 mb-6">
            <div className="w-10 h-10 rounded-xl bg-wukirsari-primary text-white flex items-center justify-center border border-white/20">
              <Mountain className="w-5 h-5" />
            </div>
            <div>
              <span className="block font-display font-extrabold text-base text-white tracking-tight">
                ADMIN LOKET
              </span>
              <span className="block text-[10px] text-sunrise-gold uppercase tracking-widest">
                Jeep Wukirsari
              </span>
            </div>
          </div>

          {/* Nav Links */}
          <nav className="space-y-1.5">
            {menuItems.map((item) => {
              const Icon = item.icon;
              const active = location.pathname === item.href;
              return (
                <Link
                  key={item.name}
                  to={item.href}
                  className={cn(
                    "flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors",
                    active
                      ? "bg-wukirsari-primary text-white shadow-sm"
                      : "text-white/70 hover:bg-white/5 hover:text-white"
                  )}
                >
                  <Icon className="w-4 h-4" />
                  <span>{item.name}</span>
                </Link>
              );
            })}
          </nav>
        </div>

        {/* Footer Sidebar */}
        <div className="pt-6 border-t border-white/10 mt-6">
          <Link
            to="/"
            className="flex items-center gap-2 text-xs text-white/60 hover:text-white transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Kembali ke Website Publik</span>
          </Link>
        </div>
      </aside>

      {/* Main Admin Content */}
      <div className="flex-1 flex flex-col min-w-0">
        <header className="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">
          <h2 className="text-base font-bold text-merapiText-primary">
            Portal Operasional Basecamp Wukirsari
          </h2>
          <div className="flex items-center gap-3 text-xs text-merapiText-secondary">
            <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Sistem Kasir Aktif</span>
          </div>
        </header>

        <main className="p-6 flex-1 overflow-y-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
