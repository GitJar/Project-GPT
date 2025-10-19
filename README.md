```md
# Next.js Prayer Template

Starter project Next.js + TypeScript untuk menampilkan jadwal sholat.

## Setup
1. Copy `.env.example` -> `.env.local` dan isi `API_BASE_URL` (dari dokumentasi Postman yang kamu punya). Tambahkan `API_KEY` jika perlu.
2. `npm install`
3. `npm run dev`
4. Buka `http://localhost:3000`

## Catatan
- Sesuaikan `lib/api.ts` path & transform dengan struktur respons API sebenarnya.
- Gunakan `/api/proxy/*` untuk menghindari CORS dan menyembunyikan API key.
- Untuk optimasi, ganti fetch di server dengan `next: { revalidate: ... }` jika ingin ISR.
```
