<?php

namespace App\Domain\Comment\Repositories;

use App\Domain\Comment\Models\Comment;
use Illuminate\Support\Collection;

interface CommentRepositoryInterface
{
    public function getByArticle(int $articleId, string $articleSource): Collection;

    public function create(array $data): Comment;
}
