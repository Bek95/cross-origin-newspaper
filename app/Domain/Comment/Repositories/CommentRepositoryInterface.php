<?php

namespace App\Domain\Comment\Repositories;

use App\Domain\Comment\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CommentRepositoryInterface
{
    public function getPaginatedByArticle(int $articleId, int $perPage = 10): LengthAwarePaginator;
    public function getByArticle(int $articleId, string $articleSource): Collection;

    public function create(array $data): Comment;
}
