<?php

namespace App\Http\Controllers;

use App\Application\Press\PressOrchestrator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ArticleViewController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['source', 'category', 'date']);

        $flatArticles = collect($this->orchestrator->fetchAllFrontpages())
            ->flatMap(fn($sourceData) => data_get($sourceData, 'articles.data', []))
            ->filter(fn($article) => !empty($article['title']));

        $filtered = $flatArticles->filter(function ($article) use ($filters) {
            if (!empty($filters['source'])) {
                $title = strtolower($article['title'] ?? '');
                if (!str_contains($title, strtolower($filters['source']))) {
                    return false;
                }
            }

            if (!empty($filters['category'])) {
                $category = strtolower(data_get($article, 'category.name', ''));
                if (!str_contains($category, strtolower($filters['category']))) {
                    return false;
                }
            }

            if (!empty($filters['date'])) {
                $articleDate = $this->extractArticleDate($article);
                $filterDate = Carbon::parse($filters['date'])->toDateString();

                if ($articleDate !== $filterDate) {
                    return false;
                }
            }

            return true;
        });

        $sorted = $filtered->sortByDesc(fn($article) => $this->extractArticleDateTime($article));

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $paginator = new LengthAwarePaginator(
            $sorted->forPage($page, $perPage)->values(),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('articles.index', [
            'articles' => $paginator,
            'filters'  => $filters,
        ]);
    }

    /**
     * Récupère la date de publication d’un article (au format Y-m-d).
     */
    private function extractArticleDate(array $article): ?string
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
     */
    private function extractArticleDateTime(array $article): Carbon
    {
        $dateField = $article['created_at'] ?? $article['published_at'] ?? null;

        try {
            return $dateField ? Carbon::parse($dateField) : Carbon::minValue();
        } catch (\Exception) {
            return Carbon::minValue();
        }
    }
}
