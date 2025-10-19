```tsx
import React from 'react';
import { PrayerTimes } from '@/types/prayer';

export default function CityCard({ city, times }: { city: {name:string}, times: PrayerTimes }) {
  return (
    <div className="p-4 rounded shadow bg-white">
      <h3 className="font-semibold">{city.name}</h3>
      <p>{times.date}</p>
      <ul>
        <li>Subuh: {times.fajr}</li>
        <li>Dzuhur: {times.dhuhr}</li>
        <li>Ashr: {times.asr}</li>
        <li>Maghrib: {times.maghrib}</li>
        <li>Isya: {times.isha}</li>
      </ul>
    </div>
  );
}
```