<?php

declare(strict_types=1);

namespace App\Domain\Comment\Services;

use App\Domain\Comment\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CommentService
{
    /**
     * Récupère les commentaires associés à un article.
     */
    public function getCommentsForArticle(int $articleId, int $perPage = 10): LengthAwarePaginator
    {
        return Comment::query()
            ->where('article_id', $articleId)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->paginate($perPage);
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
