/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        wukirsari: {
          primary: '#1E3F20',    // Deep Forest Green (Tombol Utama & Brand)
          dark: '#142B16',       // Footer & Dark Container
          hover: '#265029',      // Hover State
          sage: '#E8EFE9',       // Background Fitur & Pill
          cream: '#F7F7F2',      // Warm Background Utama
          border: '#E2EAE3',     // Soft Border
        },
        merapiText: {
          primary: '#19231A',    // Judul & Teks Kontras Tinggi
          secondary: '#526054',  // Deskripsi & Caption
          muted: '#7E8E81',      // Sub-label & Placeholder
        },
        sunrise: {
          gold: '#F59E0B',       // Aksen Bintang & Golden Hour
          amber: '#D97706',
        },
      },
      fontFamily: {
        sans: ["'Plus Jakarta Sans'", "sans-serif"],
        display: ["'Outfit'", "sans-serif"],
        accent: ["'Playfair Display'", "serif"],
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
        pill: '9999px',
      },
    },
  },
  plugins: [],
};
