<?php

namespace App\Domain\Comment\Repositories;

use App\Domain\Comment\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function getPaginatedByArticle(int $articleId, int $perPage = 10): LengthAwarePaginator
    {
        return Comment::query()
            ->where('article_id', $articleId)
            ->with('user:id,name,email')
            ->latest()
            ->paginate($perPage);
    }
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
