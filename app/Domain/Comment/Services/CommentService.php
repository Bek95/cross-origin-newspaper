<?php

declare(strict_types=1);

namespace App\Domain\Comment\Services;

use App\Domain\Comment\Models\Comment;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CommentService
{

    public function __construct(
        private CommentRepositoryInterface $commentRepository,
    ) {}
    /**
     * Récupère les commentaires associés à un article.
     */
    public function getCommentsForArticle(int $articleId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->commentRepository->getPaginatedByArticle($articleId, $perPage);
    }

    /**
     * Ajoute un commentaire pour un article donné.
     *
     * @throws \Throwable Si la création échoue.
     */
    public function addComment(int $articleId, int $userId, string $content, string $articleSource): void
    {
        try {
            Comment::create([
                'article_id'      => $articleId,
                'user_id'         => $userId,
                'content'         => $content,
                'article_source'  => $articleSource,
            ]);
        } catch (Throwable $e) {
            Log::error('Erreur lors de l’ajout d’un commentaire', [
                'article_id' => $articleId,
                'user_id'    => $userId,
                'message'    => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
