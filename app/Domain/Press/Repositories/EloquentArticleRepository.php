<?php

namespace App\Domain\Press\Repositories;

use App\Domain\Press\Models\Article;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function findById(int $id): ?Article
    {
        return Article::with('source')->find($id);
    }
}
