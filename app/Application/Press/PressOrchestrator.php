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
            $sourceName = class_basename($service);

            try {
                $articles = $service->fetchFrontpage($options);

                $results[$sourceName] = [
                    'status' => 'success',
                    'count' => count($articles ?? []),
                    'articles' => $articles,
                ];

            } catch (\Throwable $e) {
                Log::error("Erreur lors de la récupération des articles pour {$sourceName}: {$e->getMessage()}", [
                    'exception' => $e
                ]);

                $results[$sourceName] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Récupère un article spécifique.
     *
     */
    public function fetchArticle(string $source, int $id): ?array
    {
        $sourceLower = strtolower($source);

        foreach ($this->services as $service) {
            if (strtolower($service->getSourceName()) === $sourceLower) {

                if (method_exists($service, 'fetchArticleById')) {
                    return $service->fetchArticleById($id);
                }

                $articles = $service->fetchFrontpage();
                foreach ($articles as $article) {
                    if (($article['id'] ?? null) === $id) {
                        return $article;
                    }
                }

                break;
            }
        }

        return null;
    }
}
