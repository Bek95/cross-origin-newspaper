<hr>
@php
    $recentComments = \App\Domain\Comment\Models\Comment::where('article_id', $article->id)
        ->with('user:id,name')
        ->latest()
        ->take(2)
        ->get();
@endphp

@if ($recentComments->isNotEmpty())
    <div class="mt-3">
        <h6>Derniers commentaires :</h6>
        <ul class="list-group list-group-flush">
            @foreach ($recentComments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->name }}</strong> :
                    {{ Str::limit($comment->content, 100) }}
                    <br>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-2">
        <a href="{{ route('comments.index', $article->id) }}" class="btn btn-link p-0">
            Voir tous les commentaires →
        </a>
    </div>
@else
    <p class="text-muted fst-italic">Aucun commentaire pour cet article.</p>
@endif
