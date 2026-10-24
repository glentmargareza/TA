import HeroSection from '@/components/home/HeroSection';
import ValueBar from '@/components/home/ValueBar';
import PackageSection from '@/components/home/PackageSection';
import WhyUsSection from '@/components/home/WhyUsSection';
import GallerySection from '@/components/home/GallerySection';
import TestimonialSection from '@/components/home/TestimonialSection';

export default function HomePage() {
  return (
    <div className="space-y-0">
      <HeroSection />
      <ValueBar />
      <PackageSection />
      <WhyUsSection />
      <GallerySection />
      <TestimonialSection />
    </div>
  );
}
