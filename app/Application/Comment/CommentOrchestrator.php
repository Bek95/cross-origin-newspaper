<?php

namespace App\Application\Comment;

use App\Domain\Comment\Services\CommentService;

class CommentOrchestrator
{
    public function __construct(
        protected CommentService $commentService
    ) {}

    public function listForArticle(int $articleId, int $perPage = 10)
    {
        return $this->commentService->getCommentsForArticle($articleId, $perPage);
    }
}
