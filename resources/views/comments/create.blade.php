@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Ajouter un commentaire</h2>

        <form action="{{ route('comments.store', ['articleId' => $articleId]) }}" method="POST">
            @csrf
            <input type="hidden" name="source" value="{{ $source }}">
            <div class="mb-3">
                <label for="content" class="form-label">Commentaire</label>
                <textarea name="content" id="content" rows="5" class="form-control">{{ old('content') }}</textarea>
                @error('content')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Envoyer</button>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
