'use client';

import { useState } from "react";
import { sendUrl } from "../lib/api";
import Alert from "./Alert";
import "../styles/UrlForm.css";

export default function UrlForm() {
  const [url, setUrl] = useState("");
  const [alert, setAlert] = useState<{ message: string; type?: "success" | "error" } | null>(null);

  const handleSubmit = async () => {
    if (!url) {
      setAlert({ message: "Veuillez entrer une URL", type: "error" });
      return;
    }

    try {
      const res = await sendUrl(url);
      setAlert({ message: res.message || res.error, type: res.error ? "error" : "success" });
    } catch {
      setAlert({ message: "Erreur de communication avec le backend", type: "error" });
    }
  };

  return (
    <div className="container">
      <h1>Short URL Demo</h1>
      <input
        type="text"
        placeholder="Entrez votre URL"
        value={url}
        onChange={(e) => setUrl(e.target.value)}
      />
      <button onClick={handleSubmit}>Envoyer</button>

      {alert && <Alert message={alert.message} type={alert.type} onClose={() => setAlert(null)} />}
    </div>
  );
}
