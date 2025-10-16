<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container d-flex flex-end">
        <div>
            <a class="navbar-brand" href="{{ route('home') }}">Cross-Origin Newspaper</a>
        </div>
        <div>
            <a class="navbar-brand" href="{{ route('home') }}">Accueil</a>
            <a class="navbar-brand" href="{{ route('articles.index') }}">Articles</a>
        </div>
    </div>

</nav>

<div class="container">
    @yield('content')
</div>

<footer class="text-center mt-4 mb-4">
    <small>&copy; {{ date('Y') }} Cross-Origin Newspaper</small>
</footer>
</body>
</html>
