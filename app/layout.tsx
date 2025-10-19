```tsx
import './globals.css';
import React from 'react';

export const metadata = {
  title: 'Jadwal Sholat - Jateng & DIY',
  description: 'Tampilkan jadwal sholat untuk kota-kota di Jawa Tengah & Yogyakarta'
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="id">
      <body>
        <main className="min-h-screen bg-slate-50 p-6">
          <div className="max-w-3xl mx-auto">{children}</div>
        </main>
      </body>
    </html>
  );
}
```