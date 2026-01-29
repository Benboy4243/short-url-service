<?php
declare(strict_types=1);

class Database
{
    private PDO $pdo; // Php data object

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Vérifie si le slug existe déjà
     */
    public function slugExists(string $slug): bool
    {
        $sqlRequest  = $this->pdo->prepare("SELECT COUNT(*) FROM short_urls WHERE slug = ?");
        $sqlRequest ->execute([$slug]);
        return $sqlRequest ->fetchColumn() > 0;
    }
    
    /**
     * Insère un ShortUrl dans la bd
     */
    public function insertShortUrl(ShortUrl $shortUrl): ShortUrl
    {
        $sqlRequest  = $this->pdo->prepare(
            "INSERT INTO short_urls (slug, original_url, created_at, expires_at, clicks)
            VALUES (:slug, :original_url, :created_at, :expires_at, :clicks)"
        );

        $sqlRequest ->execute([
            ':slug' => $shortUrl->slug,
            ':original_url' => $shortUrl->originalUrl,
            ':created_at' => $shortUrl->createdAt,
            ':expires_at' => $shortUrl->expiresAt,
            ':clicks' => $shortUrl->clicks
        ]);

        // Récupère l'ID auto-incrémenté généré par MySQL
        $shortUrl->id = (int)$this->pdo->lastInsertId();

        return $shortUrl;
    }

    /**
     * Retourne l'URL complète à partir d'un slug
     */
    public function findShortUrlBySlug(string $slug): ?ShortUrl
    {
        $sqlRequest  = $this->pdo->prepare(
            "SELECT id, slug, original_url, created_at, expires_at, clicks
            FROM short_urls WHERE slug = ?"
        );
        $sqlRequest ->execute([$slug]);
        $row = $sqlRequest ->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new ShortUrl(
            id: (int)$row['id'],
            slug: $row['slug'],
            originalUrl: $row['original_url'],
            createdAt: $row['created_at'],
            expiresAt: $row['expires_at'],
            clicks: (int)$row['clicks']
        );
    }   
}
