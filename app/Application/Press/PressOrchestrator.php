<?php

namespace App\Application\Press;

use App\Domain\Press\Services\SourceServices\SourceServiceInterface;
use Carbon\Carbon;

class PressOrchestrator
{

    public function __construct(
        protected array $services // Tableau de services SourceServiceInterface
    ) {}

    public function fetchAllFrontpages(string $date): array
    {
        $allArticles = [];

        foreach ($this->services as $service) {
            $allArticles[] = $service->fetchFrontpage($date);
        }

        return $allArticles;
    }
}
