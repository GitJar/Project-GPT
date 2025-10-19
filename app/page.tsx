```tsx
import React from 'react';
import CitySelector from '@/components/CitySelector';

export default function HomePage() {
  return (
    <section>
      <h1 className="text-2xl font-bold mb-4">Jadwal Sholat - Jawa Tengah & Yogyakarta</h1>
      <p className="mb-4">Pilih kota untuk melihat jadwal hari ini.</p>
      <CitySelector />
    </section>
  );
}
```