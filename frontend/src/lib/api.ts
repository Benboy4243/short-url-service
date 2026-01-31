export interface ShortUrlResponse {
  id: number;
  slug: string;
  originalUrl: string;
  createdAt: string;
  expiresAt: string | null;
  clicks: number;
}

const API_URL = import.meta.env.VITE_API_URL as string;

export async function shortenUrl(url: string, expiresAt?: string): Promise<ShortUrlResponse> {
  const response = await fetch(`${API_URL}/shorten`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ url, expiresAt }),
  });

  if (!response.ok) {
    const error = await response.json();
    throw new Error(error.error || 'Erreur inconnue');
  }

  const json = await response.json();
  return json.data;
}

// export async function shortenUrl(url: string, expiresAt?: string): Promise<ShortUrlResponse> {
//   const response = await fetch('http://localhost:8000/shorten', {
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json',
//     },
//     body: JSON.stringify({ url, expiresAt }),
//   });

//   if (!response.ok) {
//     const error = await response.json();
//     throw new Error(error.error || 'Erreur inconnue');
//   }

//   const json = await response.json();
//   return json.data;
// }
