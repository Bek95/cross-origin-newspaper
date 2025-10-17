@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Gestion des utilisateurs</h1>

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Changer de rôle</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="form-select">
                                <option value="reader_simple" @selected($user->role === 'reader_simple')>Lecteur simple</option>
                                <option value="reader" @selected($user->role === 'reader')>Lecteur</option>
                                <option value="admin" @selected($user->role === 'admin')>Administrateur</option>
                            </select>
                            <button class="btn btn-primary btn-sm">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
