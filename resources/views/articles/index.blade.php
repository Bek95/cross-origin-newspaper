@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col justify-start bg-gray-50 p-6">
        <h1 class="text-2xl font-bold mb-6">Articles</h1>

        @if($articles->isEmpty())
            <p class="text-gray-500 text-center mt-auto mb-auto">Aucun article disponible.</p>
        @else
            <div class="space-y-4 flex-1">
                @foreach($articles as $article)
                    <div class="p-4 bg-white shadow rounded">
                        <h2 class="text-lg font-semibold">{{ $article['title'] }}</h2>

                        @if(isset($article['author']['name']))
                            <p class="text-gray-600 text-sm">Auteur : {{ $article['author']['name'] }}</p>
                        @endif

                        @if(isset($article['category']['name']))
                            <p class="text-gray-600 text-sm">Catégorie : {{ $article['category']['name'] }}</p>
                        @endif

                        <p class="mt-2">{{ $article['content'] ?? 'Pas de contenu.' }}</p>
                        @if(isset($article['created_at']))
                            <p class="text-gray-400 text-xs mt-1">Publié le : {{ \Carbon\Carbon::parse($article['created_at'])->format('d/m/Y H:i') }}</p>
                        @elseif(isset($article['published_at']))
                            <p class="text-gray-400 text-xs mt-1">Publié le : {{ \Carbon\Carbon::parse($article['published_at'])->format('d/m/Y H:i') }}</p>
                        @endif

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection
