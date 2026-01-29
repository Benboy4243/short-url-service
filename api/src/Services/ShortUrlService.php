<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/ShortUrl.php';
require_once __DIR__ . '/Database.php';

class ShortUrlService
{
    private Database $db;
    private int $slugLength = 6;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Génère un slug unique
     */
    public function generateSlug(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $slug = '';
            for ($i = 0; $i < $this->slugLength; $i++) {
                $slug .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while ($this->db->slugExists($slug));

        return $slug;
    }

    /**
     * Crée un ShortUrl et le stocke en DB
     */
    public function createShortUrl(string $originalUrl, ?string $expiresAt = null): ShortUrl
    {
        $slug = $this->generateSlug();

        $shortUrl = new ShortUrl(
            id: null,
            slug: $slug,
            originalUrl: $originalUrl,
            createdAt: date('Y-m-d H:i:s'),
            expiresAt: $expiresAt,
            clicks: 0
        );

        return $this->db->insertShortUrl($shortUrl);
    }

    /**
     * Retourne l'URL complète à partir d'un slug
     */
    public function getOriginalUrl(string $slug): ?ShortUrl
    {
        return $this->db->findShortUrlBySlug($slug);
    }
}
