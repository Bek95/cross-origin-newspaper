<?php

namespace App\Domain\Press\Services\DateServices;

use Carbon\Carbon;

class DateExtractionService
{
    /**
     * Récupère la date simple (Y-m-d) d'un article.
     *
     * @param array $article
     * @return string|null
     */
    public function extractArticleDate(array $article): ?string
    {
        $dateField = $article['created_at'] ?? $article['published_at'] ?? null;

        try {
            return $dateField ? Carbon::parse($dateField)->toDateString() : null;
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Récupère la date/heure complète pour le tri (retourne un Carbon).
     *
     * @param array $article
     * @return Carbon
     */
    public function extractArticleDateTime(array $article): Carbon
    {
        $dateField = $article['created_at'] ?? $article['published_at'] ?? null;

        try {
            return $dateField ? Carbon::parse($dateField) : Carbon::minValue();
        } catch (\Exception) {
            return Carbon::minValue();
        }
    }
}
