export async function sendUrl(url: string) {
  const res = await fetch("http://localhost:8000/shorten", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ url }),
  });
  return res.json();
}
