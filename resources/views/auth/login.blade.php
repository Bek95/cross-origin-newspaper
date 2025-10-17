@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Connexion</h2>
        @if ($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input">
                <label class="form-check-label">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
@endsection
