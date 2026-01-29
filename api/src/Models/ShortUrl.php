<?php
declare(strict_types=1);

class ShortUrl
{
    public ?int $id;
    public string $slug;
    public string $originalUrl;
    public string $createdAt;
    public ?string $expiresAt;
    public int $clicks;

    public function __construct(?int $id, string $slug, string $originalUrl, string $createdAt, ?string $expiresAt = null, int $clicks = 0) 
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->originalUrl = $originalUrl;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
        $this->clicks = $clicks;
    }

    /**
     * Vérifie si le lien est expiré
     */
    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }
        return strtotime($this->expiresAt) < time();
    }

    /**
     * Incrémente le compteur de clics
     */
    public function incrementClicks(): void
    {
        $this->clicks++;
    }
}
