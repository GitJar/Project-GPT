```tsx
import React from 'react';
import { fetchPrayerTimes } from '@/lib/api';
import type { Metadata } from 'next';

type Props = { params: { id: string } };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  return { title: `Jadwal - ${params.id}` };
}

export default async function CityPage({ params }: Props) {
  try {
    const times = await fetchPrayerTimes(params.id);
    return (
      <div>
        <h2 className="text-xl font-semibold">Kota: {params.id}</h2>
        <p>Tanggal: {times.date}</p>
        <ul className="mt-3">
          <li>Subuh: {times.fajr}</li>
          <li>Dzuhur: {times.dhuhr}</li>
          <li>Ashr: {times.asr}</li>
          <li>Maghrib: {times.maghrib}</li>
          <li>Isya: {times.isha}</li>
        </ul>
      </div>
    );
  } catch (err) {
    return <div>Gagal memuat jadwal: {(err as Error).message}</div>;
  }
}
```
