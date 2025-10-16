<?php

use App\Http\Controllers\ArticleViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleViewController::class, 'index']);
