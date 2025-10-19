```ts
export type PrayerTimes = {
  date: string; // YYYY-MM-DD
  fajr: string;
  dhuhr: string;
  asr: string;
  maghrib: string;
  isha: string;
};

export type City = {
  id: string;
  name: string;
  province?: string;
};
```