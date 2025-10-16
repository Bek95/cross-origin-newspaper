<?php

namespace App\Providers;

use App\Application\Press\PressOrchestrator;
use App\Domain\Press\Repositories\ArticleRepositoryInterface;
use App\Domain\Press\Repositories\EloquentArticleRepository;
use Illuminate\Support\ServiceProvider;
use App\Domain\Press\Services\{
    SourceServices\LeMondeService,
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LeMondeService::class);

        $this->app->singleton(PressOrchestrator::class, function ($app) {
            return new PressOrchestrator([
                $app->make(LeMondeService::class),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
