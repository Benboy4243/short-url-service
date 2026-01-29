<?php
declare(strict_types=1);

require_once __DIR__ . '/../Services/ShortUrlService.php';

class ShortUrlController
{
    private ShortUrlService $service;

    public function __construct(ShortUrlService $service)
    {
        $this->service = $service;
    }

    // POST /shorten
    public function shorten(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $url = trim($data['url'] ?? '');
        // Ici on vera si l'objet JSOn enovoyé par le frontend à une date d'expiration.
        $expiresAt = $data['expiresAt'] ?? null; 

        if ($url === '') {
            http_response_code(400);
            echo json_encode(['error' => 'URL vide']);
            return;
        }

        // TODO: Valider l'URL (format, etc.)

        // Crée le ShortUrl
        $shortUrl = $this->service->createShortUrl($url, $expiresAt);

        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id' => $shortUrl->id,
                'slug' => $shortUrl->slug,
                'originalUrl' => $shortUrl->originalUrl,
                'createdAt' => $shortUrl->createdAt,
                'expiresAt' => $shortUrl->expiresAt,
                'clicks' => $shortUrl->clicks
            ]
        ]);
    }

    // GET /{slug} -> redirection
    public function redirect(string $slug): void
    {
        $shortUrl = $this->service->getOriginalUrl($slug);

        if (!$shortUrl || $shortUrl->isExpired() != null ) {
            http_response_code(404);
            echo "Lien introuvable ou expiré.";
            return;
        }

        // TODO: Compléter la logique de redirection
    }
}
