<?php

namespace App\Domain\Press\Repositories;

use App\Domain\Press\Models\Article;

interface ArticleRepositoryInterface
{
    public function findById(int $id): ?Article;

}
