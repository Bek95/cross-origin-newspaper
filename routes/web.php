<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleViewController;
use App\Http\Controllers\HomeViewController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;


// admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    });

Route::get('/', [HomeViewController::class, 'home'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/articles', [ArticleViewController::class, 'index'])->name('articles.index');
});


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    Route::get('login', [LoginController::class, 'show'])->name('login.show');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

Route::post('logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');
