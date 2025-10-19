```ts
import { PrayerTimes } from '@/types/prayer';

const API_BASE = process.env.API_BASE_URL;
const API_KEY = process.env.API_KEY;

async function safeFetch(url: string) {
  const headers: Record<string,string> = {};
  if (API_KEY) headers['x-api-key'] = API_KEY;

  const res = await fetch(url, { headers, cache: 'no-store' });
  if (!res.ok) throw new Error(`API returned ${res.status}`);
  return res.json();
}

export async function fetchPrayerTimes(cityId: string, date?: string): Promise<PrayerTimes> {
  if (!API_BASE) throw new Error('API_BASE_URL not defined');
  const d = date ?? new Date().toISOString().slice(0,10);

  // NOTE: sesuaikan path & query parameter ini dengan dokumentasi Postman
  const url = `${API_BASE}/jadwal?city=${encodeURIComponent(cityId)}&date=${d}`;
  const json = await safeFetch(url);

  // Contoh transform. Sesuaikan dengan struktur respons nyata.
  return {
    date: d,
    fajr: json.fajr ?? json.subuh ?? json.times?.fajr ?? '—',
    dhuhr: json.dhuhr ?? json.dzuhur ?? json.times?.dhuhr ?? '—',
    asr: json.asr ?? json.ashar ?? json.times?.asr ?? '—',
    maghrib: json.maghrib ?? json.magrib ?? json.times?.maghrib ?? '—',
    isha: json.isha ?? json.isya ?? json.times?.isha ?? '—'
  };
}

export async function fetchCities(): Promise<{id:string,name:string}[]> {
  if (!API_BASE) throw new Error('API_BASE_URL not defined');
  const url = `${API_BASE}/cities`; // sesuaikan
  return safeFetch(url);
}
```
