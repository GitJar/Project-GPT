```ts
import { NextResponse } from 'next/server';

// Route ini berfungsi sebagai proxy sederhana: client memanggil /api/proxy/*
// dan server meneruskan ke API eksternal. Ini menghindari masalah CORS

export async function GET(request: Request) {
  const url = new URL(request.url);
  const path = url.pathname.replace('/api/proxy', '');
  const search = url.search;

  const API_BASE = process.env.API_BASE_URL;
  const API_KEY = process.env.API_KEY;
  if (!API_BASE) return NextResponse.json({ error: 'API_BASE_URL not configured' }, { status: 500 });

  const target = `${API_BASE}${path}${search}`;
  const headers: Record<string,string> = {};
  if (API_KEY) headers['x-api-key'] = API_KEY;

  const res = await fetch(target, { headers });
  const text = await res.text();

  return new NextResponse(text, {
    status: res.status,
    headers: { 'content-type': res.headers.get('content-type') ?? 'application/json' }
  });
}
```
