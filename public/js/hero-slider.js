/**
 * Hero Slider - Jeep Wukirsari Adventure Tour
 * Mengatur pergantian foto latar belakang dan teks lokasi saat angka 01-05 diklik
 */

let currentHeroSlide = 0;
const totalHeroSlides = 5;
let heroSlideTimer = null;

// Daftar nama lokasi destinasi sesuai foto 01 - 05
const heroSlideLocations = [
    'Susur Kali Gendol', // Slide 01 (img/beranda.jpeg)
    'Spot Sunrise',  // Slide 02 (img/sunrise_trip.jpeg)
    'Jalur Offroad',      // Slide 03 (img/long_trip.jpeg)
    'Museum Kampung Hilang', // Slide 04 (img/medium_trip_a.jpeg)
    'Manuver Air'      // Slide 05 (img/manuver_air.png)
];

/**
 * Pindah slide foto berdasarkan nomor indeks (0 sampai 4)
 * @param {number} index
 */
function goToHeroSlide(index) {
    if (index === currentHeroSlide) return;

    // 1. Sembunyikan foto sebelumnya dengan transisi fade-out
    const prevSlide = document.getElementById('heroSlide-' + currentHeroSlide);
    if (prevSlide) {
        prevSlide.classList.remove('opacity-100', 'z-10');
        prevSlide.classList.add('opacity-0', 'z-0');
    }

    // 2. Tampilkan foto baru dengan transisi fade-in
    const nextSlide = document.getElementById('heroSlide-' + index);
    if (nextSlide) {
        nextSlide.classList.remove('opacity-0', 'z-0');
        nextSlide.classList.add('opacity-100', 'z-10');
    }

    // 3. Pindahkan garis hijau aktif ke angka yang sedang diklik (01 - 05)
    const indicators = document.querySelectorAll('#heroSlideButtons .hero-indicator');
    indicators.forEach(function (btn, i) {
        if (i === index) {
            btn.className = 'hero-indicator font-bold text-white border-b-2 border-emerald-400 pb-0.5 transition-all cursor-pointer';
        } else {
            btn.className = 'hero-indicator text-white/60 hover:text-white border-b-2 border-transparent pb-0.5 transition-all cursor-pointer';
        }
    });

    // 4. Update teks pill lokasi di samping ikon pin hijau
    const locText = document.getElementById('heroSlideLocationText');
    if (locText && heroSlideLocations[index]) {
        locText.innerText = heroSlideLocations[index];
    }

    currentHeroSlide = index;
    restartHeroAutoSlide();
}

/**
 * Otomatis bergeser ke slide berikutnya
 */
function nextHeroSlide() {
    const nextIndex = (currentHeroSlide + 1) % totalHeroSlides;
    goToHeroSlide(nextIndex);
}

/**
 * Reset interval timer auto-slide setiap 5 detik
 */
function restartHeroAutoSlide() {
    if (heroSlideTimer) clearInterval(heroSlideTimer);
    heroSlideTimer = setInterval(nextHeroSlide, 5000);
}

// Jalankan auto-slide saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    restartHeroAutoSlide();
});

