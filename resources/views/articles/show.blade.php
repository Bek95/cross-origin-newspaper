@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1>{{ $article['title'] }}</h1>
        <p class="text-muted">Publié le {{ \Carbon\Carbon::parse($article['created_at'] ?? $article['published_at'])->format('d/m/Y H:i') }}</p>
        <div class="mb-4">{{ $article['content'] }}</div>

        <hr>
        <h3>Commentaires</h3>

        @if($comments->isEmpty())
            <p>Aucun commentaire pour le moment.</p>
        @else
            <ul class="list-group mb-4">
                @foreach($comments as $comment)
                    <li class="list-group-item">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        <p>{{ $comment->content }}</p>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(auth()->user()->role === 'lecteur')
            <form action="{{ route('comments.store', $article['id']) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="content" class="form-label">Ajouter un commentaire</label>
                    <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        @endif
    </div>
@endsection
