@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col justify-start bg-gray-50 p-6">
        <h1 class="text-2xl font-bold mb-6">Articles</h1>

        <form method="GET" action="{{ route('articles.index') }}" class="row g-3 mb-4">
            <div class="d-flex justify-content-center">
                <div class="col-md-3">
                    <label class="form-label">Journal</label>
                    <select name="source" class="form-select">
                        <option value="">Tous</option>
                        <option value="{{ \App\Domain\Press\Services\SourceServices\LeMondeService::NAME }}" {{ request('source') == \App\Domain\Press\Services\SourceServices\LeMondeService::NAME ? 'selected' : '' }}>Le Monde</option>
                        <option value={{ \App\Domain\Press\Services\SourceServices\LeParisienService::NAME }} {{ request('source') == \App\Domain\Press\Services\SourceServices\LeParisienService::NAME ? 'selected' : '' }}>Le Parisien</option>
                        <option value={{ \App\Domain\Press\Services\SourceServices\LequipeService::NAME }} {{ request('source') == \App\Domain\Press\Services\SourceServices\LequipeService::NAME ? 'selected' : '' }}>L'Équipe</option>
                        <option value="{{ \App\Domain\Press\Services\SourceServices\LiberationService::NAME }}" {{ request('source') ==  \App\Domain\Press\Services\SourceServices\LiberationService::NAME  ? 'selected' : '' }}>Libération</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Catégorie</label>
                    <input type="text" name="category" value="{{ request('category') }}" class="form-control" placeholder="ex: Sport">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date (JJ-MM-AAAA)</label>
                    <input type="date" name="date" value="{{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d-m-Y') : '' }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Mots-clés</label>
                    <input type="text" name="keywords" value="{{ $filters['keywords'] ?? '' }}" class="form-control" placeholder="Ex: sport, politique">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Réinitialiser</a>
            </div>

        </form>

        @if($articles->isEmpty())
            <p class="text-gray-500 text-center mt-auto mb-auto">Aucun article disponible.</p>
        @else
            <div class="space-y-4 flex-1">
                @foreach($articles as $article)
                    <div class="p-4 bg-white shadow rounded">
                        <h2 class="text-lg font-semibold">{{ $article->title }}</h2>

                        @if(is_array($article->authors))
                            <p class="text-gray-600 text-sm">Auteur : {{ implode(' - ', $article->authors) }}</p>
                        @endif

                        <p class="text-gray-600 text-sm">Catégorie : {{ $article->category }}</p>

                        <p class="mt-2">{{ $article->content ?? 'Pas de contenu.' }}</p>
                        <p class="text-gray-400 text-xs mt-1">Publié le : {{ \Carbon\Carbon::parse($article->publishedAt)->format('d/m/Y') }}</p>

                        {{--Section commentaires --}}
                        @include('articles._comments', ['article' => $article])

                    @auth
                            @if(in_array(auth()->user()->role, ['reader', 'admin']))
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('comments.create', ['articleId' => $article->id]) }}?source={{ urlencode($article->title) }}"
                                       class="btn btn-primary mb-3">
                                        Ajouter un commentaire
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection

