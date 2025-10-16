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
    public function fetchAllFrontpages(): array
    {
        $results = [];

        foreach ($this->services as $service) {
            try {
                $sourceName = class_basename($service);

                $articles = $service->fetchFrontpage();

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
}
