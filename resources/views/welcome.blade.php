@extends('layouts.app')

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">Bienvenue sur <span class="text-primary">Cross-Origin Newspaper</span></h1>
            <p class="lead text-muted mb-4">
                Retrouvez les derniers articles des plus grands journaux français — triés, filtrés, et toujours à jour.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg shadow-sm px-4">
                    <i class="bi bi-newspaper me-2"></i> Voir les articles
                </a>
            </div>

            <hr class="my-5 w-50 mx-auto">

            <div class="row justify-content-center text-start">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-3 text-center text-primary">
                                Fonctionnalités principales
                            </h5>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-2">Agrégation d’articles depuis plusieurs sources</li>
                                <li class="mb-2">Tri automatique par date de publication</li>
                                <li class="mb-2">Filtrage par journal, catégorie et date</li>
                                <li class="mb-2">Pagination propre et rapide</li>
                                <li>Interface responsive et minimaliste</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 text-muted small">
                &copy; {{ date('Y') }} Cross-Origin Newspaper
            </footer>
        </div>
    </div>
@endsection
