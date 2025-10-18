<?php

namespace App\Http\Controllers;

use App\Application\Comment\CommentOrchestrator;
use App\Application\Press\PressOrchestrator;
use App\Domain\Press\DTO\ArticleData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ArticleViewController extends Controller
{
    public function __construct(
        protected PressOrchestrator $pressOchestrator,
        protected CommentOrchestrator $commentOrchestrator
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['source', 'category', 'date', 'keywords']);

        $allArticles = $this->pressOchestrator->fetchAllFrontpages();

        $flatArticles = collect($allArticles)
            ->flatMap(fn($sourceData) => $sourceData['articles'] ?? [])
            ->filter(function (ArticleData $article) use ($filters) {

                // Filtrage source
                if (!empty($filters['source']) && !str_contains(strtolower($article->source), strtolower($filters['source']))) {
                    return false;
                }

                // Filtrage catégorie (partiel)
                if (!empty($filters['category'])) {
                    $category = strtolower($article->category ?? '');
                    if (!str_contains($category, strtolower($filters['category']))) {
                        return false;
                    }
                }

                // Filtrage date
                if (!empty($filters['date'])) {
                    $articleDate = $article->publishedAt?->format('Y-m-d');
                    if ($articleDate !== $filters['date']) {
                        return false;
                    }
                }

                // Filtrage mots-clés
                if (!empty($filters['keywords'])) {
                    $keywords = array_map('trim', explode(',', $filters['keywords']));
                    $content = strtolower(($article->title ?? '') . ' ' . ($article->content ?? ''));
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
            ->sortByDesc(fn(ArticleData $a) => $a->publishedAt ?? now())->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $total = $flatArticles->count();

        $maxPage = (int) ceil($total / $perPage);
        $page = min($page, $maxPage); // <-- ne pas dépasser la dernière page

        $flatArticles = collect($flatArticles)->values();

        $paginator = new LengthAwarePaginator(
            $flatArticles->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('articles.index', [
            'articles' => $paginator,
            'filters' => $filters,
        ]);
    }

    public function show(string $source, int $id)
    {
        $article = $this->pressOchestrator->fetchArticle($source, $id);
        if (!$article) {
            abort(404);
        }

        $comments = $this->commentOrchestrator->listForArticle($id, $source);

        return view('articles.show', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }


}
