<?php

namespace App\Domain\Press\DTO;

use Carbon\Carbon;

/**
 * Data Transfer Object représentant un article de presse normalisé.
 */
class ArticleData
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $content,
        public readonly string $source,
        public readonly ?string $category,
        public readonly Carbon $publishedAt,
        public readonly array $keywords = [],
        public readonly array $authors = [],
        public readonly string $url,
    ) {}

    /**
     * Crée une instance à partir d’un tableau brut (ex: API externe).
     */
    public static function fromArray(array $data, string $source): self
    {
        return new self(
            id: $data['id'] ?? 0,
            title: $data['headlines']['basic']
            ?? $data['title']
            ?? 'Sans titre',
            content: $data['content'] ?? '',
            source: $source,
            category: $data['category']['name']
            ?? $data['keywords'][0]
            ?? null,
            publishedAt: self::parseDate($data),
            keywords: $data['keywords'] ?? [],
            authors: self::extractAuthors($data),
            url: $data['url'] ?? '',
        );
    }

    /**
     * Extrait et normalise la date selon la structure rencontrée.
     */
    protected static function parseDate(array $data): Carbon
    {
        if (isset($data['publish_date']) && is_numeric($data['publish_date'])) {
            return Carbon::createFromTimestamp($data['publish_date']);
        }

        if (!empty($data['publishedAt'])) {
            return Carbon::parse($data['publishedAt']);
        }

        return now();
    }

    /**
     * Extrait la liste des auteurs depuis les crédits ou champs similaires.
     */
    protected static function extractAuthors(array $data): array
    {
        // Cas LeParisien : tableau de crédits avec type/nom
        if (isset($data['credits']) && is_array($data['credits'])) {
            return collect($data['credits'])
                ->filter(fn($c) => ($c['type'] ?? '') === 'author')
                ->pluck('name')
                ->values()
                ->all();
        }

        // Cas classique : champ "authors" déjà existant
        if (isset($data['authors'])) {
            return is_array($data['authors'])
                ? $data['authors']
                : explode(',', (string) $data['authors']);
        }

        return [];
    }

    /**
     * Retourne une représentation simplifiée (utile pour les vues).
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source,
            'category' => $this->category,
            'publishedAt' => $this->publishedAt->toDateTimeString(),
            'keywords' => $this->keywords,
            'authors' => $this->authors,
            'url' => $this->url,
        ];
    }
}
