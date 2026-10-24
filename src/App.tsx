import { BrowserRouter, Routes, Route } from 'react-router-dom';
import MainLayout from '@/components/layout/MainLayout';
import AdminLayout from '@/components/layout/AdminLayout';

import HomePage from '@/pages/HomePage';
import PackageDetailPage from '@/pages/PackageDetailPage';
import BookingPage from '@/pages/BookingPage';
import BookingSuccessPage from '@/pages/BookingSuccessPage';
import CheckTicketPage from '@/pages/CheckTicketPage';
import AdminDashboardPage from '@/pages/admin/AdminDashboardPage';

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Rute Publik Wisatawan */}
        <Route path="/" element={<MainLayout />}>
          <Route index element={<HomePage />} />
          <Route path="packages/:slug" element={<PackageDetailPage />} />
          <Route path="booking" element={<BookingPage />} />
          <Route path="booking/success" element={<BookingSuccessPage />} />
          <Route path="cek-tiket" element={<CheckTicketPage />} />
        </Route>

        {/* Rute Khusus Admin & Kasir Basecamp */}
        <Route path="/admin" element={<AdminLayout />}>
          <Route index element={<AdminDashboardPage />} />
          <Route path="bookings" element={<AdminDashboardPage />} />
          <Route path="scan" element={<AdminDashboardPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
