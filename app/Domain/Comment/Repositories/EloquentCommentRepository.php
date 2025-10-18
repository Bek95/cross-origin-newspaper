<?php

namespace App\Domain\Comment\Repositories;

use App\Domain\Comment\Models\Comment;
use Illuminate\Support\Collection;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function getByArticle(int $articleId, string $articleSource): Collection
    {
        return Comment::with('user')
            ->where('article_id', $articleId)
            ->where('article_source', $articleSource)
            ->latest()
            ->get();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }
}
