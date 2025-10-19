```tsx
'use client';
import React, { useState } from 'react';
import useSWR from 'swr';

const fetcher = (url: string) => fetch(url).then(r => r.json());

export default function CitySelector() {
  const { data, error } = useSWR('/api/proxy/cities', fetcher);
  const [selected, setSelected] = useState<string | null>(null);

  if (error) return <div>Error memuat daftar kota</div>;
  if (!data) return <div>Memuat kota...</div>;

  return (
    <div>
      <select
        className="p-2 rounded border"
        value={selected ?? ''}
        onChange={(e) => setSelected(e.target.value)}
      >
        <option value="">Pilih kota...</option>
        {data.map((c: any) => (
          <option key={c.id} value={c.id}>{c.name}</option>
        ))}
      </select>

      {selected && (
        <div className="mt-4">
          <a className="underline" href={`/city/${selected}`}>Lihat jadwal {selected}</a>
        </div>
      )}
    </div>
  );
}
```