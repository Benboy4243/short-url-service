import React, { useState } from 'react';
import { shortenUrl } from '../lib/api';
import type { ShortUrlResponse } from '../lib/api';
import "../styles/UrlForm.css";

export default function UrlForm() {
  const [url, setUrl] = useState('');
  const [expiresToggle, setExpiresToggle] = useState(false);
  const [result, setResult] = useState<ShortUrlResponse | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setResult(null);

    try {
      // Si toggle activé => expiration après 30 jours
      const expiresAt = expiresToggle
        ? new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString() : undefined; // undefined = pas d'expiration

      const short = await shortenUrl(url, expiresAt);
      setResult(short);
      // alert(`Lien court créé: ${window.location.origin}/${short.slug}`);
    }

    catch (err: unknown) {
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('Erreur inconnue');
      }
    }

    finally {
      setLoading(false);
    }
  };

  return (
    <div className="url-form-container">
      <form onSubmit={handleSubmit}>
        <input
          type="url"
          placeholder="Entrez votre URL"
          value={url}
          onChange={e => setUrl(e.target.value)}
          required
        />

        <div className="toggle-container">
          <span>Expiration après 30 jours</span>
          <label className="toggle-switch">
            <input
              type="checkbox"
              checked={expiresToggle}
              onChange={e => setExpiresToggle(e.target.checked)}
            />
            <span className="slider"></span>
          </label>
        </div>


        <button type="submit" disabled={loading}>
          {loading ? 'Génération...' : 'Générer'}
        </button>
      </form>

      {error && <p className="error">{error}</p>}
      {result && (
        (() => {
          const fullUrl = `https://www.zeroaheros.ca/short-url/${result.slug}`;
          return (
            <p className="success">
              Lien court:{" "}
              <a href={fullUrl} target="_blank" rel="noopener noreferrer">
                {fullUrl}
              </a>
            </p>
          );
        })()
      )}
      
    </div>
  );
}
