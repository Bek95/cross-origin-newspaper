<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MonApp') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'MonApp') }}</a>
        @auth
            <a class="navbar-brand" href="{{ route('home') }}">Accueil</a>
            <a class="navbar-brand" href="{{ route('articles.index') }}">Articles</a>
        @endauth

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item d-flex align-items-center text-white me-3">
                        {{ Auth::user()->name }}
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Se déconnecter
                            </button>
                        </form>
                    </li>
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-3">
                                Gestion utilisateurs
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
@if (session('success') || session('error'))
    <div id="flash-message"
         class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} text-center mt-3 w-75 mx-auto">
        {{ session('success') ?? session('error') }}
    </div>

    <script>
        setTimeout(() => {
            const el = document.getElementById('flash-message');
            if (el) el.style.display = 'none';
        }, 4000);
    </script>
@endif

<main class="container flex-grow-1">
    @yield('content')
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; {{ date('Y') }} - {{ config('app.name', 'MonApp') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
