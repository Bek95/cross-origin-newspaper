<?php

namespace App\Http\Controllers;

use App\Application\Comment\CommentOrchestrator;
use App\Domain\Comment\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\View\View;


class CommentController extends \Illuminate\Routing\Controller
{
    public function __construct(
        protected CommentService $commentService
    ){}

    /**
     * Affiche la liste des commentaires pour un article.
     */
    public function index(int $articleId): View
    {
        $comments = $this->commentService->getCommentsForArticle($articleId, 10);

        return view('comments.index', [
            'comments' => $comments,
            'articleId' => $articleId,
        ]);
    }

    /**
     * Enregistre un nouveau commentaire pour un article.
     */
    public function store(Request $request, int $articleId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'source' => 'required|string|max:1000',
        ]);

        $this->commentService->addComment($articleId, auth()->id(), $validated['content'], $validated['source']);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès !');
    }

    public function create(Request $request, int $articleId): View
    {
        $source = $request->query('source');

        return view('comments.create', [
            'articleId' => $articleId,
            'source' => $source,
        ]);
    }

}
