<?php
declare(strict_types=1);

class Database
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Vérifie si le slug existe déjà
     */
    public function slugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM short_urls WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Insère un ShortUrl en base
     */
    public function insertShortUrl(ShortUrl $shortUrl): ShortUrl
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO short_urls (slug, original_url, created_at, expires_at, clicks)
            VALUES (:slug, :original_url, :created_at, :expires_at, :clicks)"
        );

        $stmt->execute([
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
    }
