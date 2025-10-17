<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Press\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:reader_simple,reader,admin',
        ]);

        $newRole = $validated['role'];
        $currentUser = auth()->user();

        if (! $currentUser->isAdmin()) {
            return redirect()->back()->with('error', "Vous n'avez pas l'autorisation de modifier les rôles.");
        }
        // empeche de retirer le dernier admin
        if ($user->isAdmin() && $newRole !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', "Impossible de retirer le dernier administrateur !");
            }
        }

        $user->update(['role' => $newRole]);

        return redirect()->back()->with('success', "Rôle de {$user->name} mis à jour avec succès !");
    }

}
