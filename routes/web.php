<?php

use App\Http\Controllers\ArticleViewController;
use App\Http\Controllers\HomeViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeViewController::class, 'home'])->name('home');
Route::get('/articles', [ArticleViewController::class, 'index'])->name('articles.index');
