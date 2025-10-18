<?php

use App\Http\Controllers\ArticleViewController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeViewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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
    //articles
    Route::get('/articles', [ArticleViewController::class, 'index'])->name('articles.index');
    Route::get('articles/{articleId}', [CommentController::class, 'index'])->name('articles.show');

    //comments
    Route::get('articles/{articleId}/comments/create', [CommentController::class, 'create'])
        ->name('comments.create');
    Route::post('articles/{articleId}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::get('articles/{articleId}/comments', [CommentController::class, 'index'])
        ->name('comments.index');

});


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    Route::get('login', [LoginController::class, 'show'])->name('login.show');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

Route::post('logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');
