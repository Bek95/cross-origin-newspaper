<?php

namespace App\Http\Controllers;

use App\Application\Press\PressOrchestrator;
use App\Domain\Press\Services\DateServices\DateExtractionService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ArticleViewController extends Controller
{
    public function __construct(
        protected PressOrchestrator $orchestrator,
        protected DateExtractionService $dateExtractionService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['source', 'category', 'date', 'keywords']);

        $allArticles = $this->orchestrator->fetchAllFrontpages();

        $flatArticles = collect($allArticles)
            ->flatMap(fn($sourceData) => $sourceData['articles']['data'] ?? [])
            ->filter(function ($article) use ($filters) {

                if (!empty($filters['source']) && !str_contains(strtolower($article['title'] ?? ''), strtolower($filters['source']))) {
                    return false;
                }

                if (!empty($filters['category'])) {
                    $category = strtolower(data_get($article, 'category.name', ''));
                    if (!str_contains($category, strtolower($filters['category']))) {
                        return false;
                    }
                }

                if (!empty($filters['date'])) {
                    $articleDate = $this->dateExtractionService->extractArticleDate($article);
                    if ($articleDate !== $filters['date']) {
                        return false;
                    }
                }

                if (!empty($filters['keywords'])) {
                    $keywords = array_map('trim', explode(',', $filters['keywords']));
                    $content = strtolower(($article['title'] ?? '') . ' ' . ($article['content'] ?? ''));
                    $match = false;
                    foreach ($keywords as $word) {
                        if ($word !== '' && str_contains($content, strtolower($word))) {
                            $match = true;
                            break;
                        }
                    }
                    if (!$match) {
                        return false;
                    }
                }

                return true;
            })
            ->sortByDesc(fn($a) => $this->dateExtractionService->extractArticleDateTime($a));

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $paginator = new LengthAwarePaginator(
            $flatArticles->forPage($page, $perPage)->values(),
            $flatArticles->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('articles.index', [
            'articles' => $paginator,
            'filters' => $filters,
        ]);
    }
}
