<?php

namespace App\Domain\Comment\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Comment\Repositories\CommentRepositoryInterface;
use App\Domain\Comment\Repositories\EloquentCommentRepository;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CommentRepositoryInterface::class,
            EloquentCommentRepository::class
        );
    }
}
