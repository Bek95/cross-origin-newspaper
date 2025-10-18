@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Commentaires de l’article #{{ $articleId }}</h2>

        @if ($comments->isEmpty())
            <p class="text-muted">Aucun commentaire pour cet article.</p>
        @else
            <ul class="list-group mb-4">
                @foreach ($comments as $comment)
                    <li class="list-group-item">
                        <strong>{{ $comment->user->name }}</strong>
                        <p>{{ $comment->content }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
        <div>
            <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg shadow-sm px-4">
                <i class="bi bi-newspaper me-2"></i> retour
            </a>
        </div>
    </div>
@endsection
