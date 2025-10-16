<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domain\Press\Repositories\ArticleRepositoryInterface;
use App\Domain\Press\Models\Source;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
//        protected ArticleRepositoryInterface $articles
    ) {}

    public function index(): JsonResponse
    {
        $data = [
            "id" => 1,
            "source_id" => 2,
            "title" => "Titre de l'article",
            "author" => "Nom de l'auteur",
            "category" => "Politique",
            "content" => "Contenu complet de l'article...",
            "published_at" => "2025-10-16T12:30:00.000000Z",
            "url" => "https://www.lemonde.fr/article/123",
            "created_at" => "2025-10-16T13:00:00.000000Z",
            "updated_at" => "2025-10-16T13:00:00.000000Z",
            "source" => [
                "id" => 2,
                "name" => "Le Monde",
                "slug" => "le-monde",
                "website" => "https://www.lemonde.fr",
                "created_at" => "2025-10-16T10:00:00.000000Z",
                "updated_at" => "2025-10-16T10:00:00.000000Z"
            ]

        ];


//        $data = $this->articles->all();
        return response()->json($data);



    }

    public function show(int $id): JsonResponse
    {
        $article = $this->articles->findById($id);

        if (! $article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }

    public function sources(): JsonResponse
    {
        $sources = Source::all();
        return response()->json($sources);
    }

    public function articlesBySource(int $id): JsonResponse
    {
        $source = Source::find($id);
        if (! $source) {
            return response()->json(['message' => 'Source not found'], 404);
        }

        $articles = $this->articles->findBySource($id);

        return response()->json($articles);
    }
}
