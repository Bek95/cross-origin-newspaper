<?php

namespace App\Application\Press;

use App\Domain\Press\Services\SourceServices\SourceServiceInterface;
use Illuminate\Support\Facades\Log;

class PressOrchestrator
{
    /**
     * @param SourceServiceInterface[] $services
     */
    public function __construct(
        protected array $services // Tableau de services implémentant SourceServiceInterface
    ) {}

    /**
     * Récupère les articles de une pour toutes les sources.
     */
    public function fetchAllFrontpages(?array $options = null): array
    {
        $results = [];

        foreach ($this->services as $service) {
            try {
                $sourceName = class_basename($service);

                $articles = $service->fetchFrontpage($options);

                $results[$sourceName] = [
                    'status' => 'success',
                    'count' => count($articles ?? []),
                    'articles' => $articles,
                ];

            } catch (\Throwable $e) {
                Log::error("Erreur lors de la récupération des articles pour {$sourceName}: {$e->getMessage()}");

                $results[$sourceName] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    public function fetchArticle(string $source, int $id): ?array
    {
        foreach ($this->services as $service) {
            if (strtolower($service->getSourceName()) === strtolower($source)) {
                $articles = $service->fetchFrontpage();
                foreach ($articles as $article) {
                    if (($article['id'] ?? null) === $id) {
                        return $article;
                    }
                }
            }
        }

        return null;
    }

}
