<?php

namespace App\Providers;

use App\Application\Press\PressOrchestrator;
use Illuminate\Support\ServiceProvider;
use App\Domain\Press\Services\{SourceServices\LeMondeService,
    SourceServices\LeParisienService,
    SourceServices\LequipeService,
    SourceServices\LiberationService};

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
                $app->make(LequipeService::class),
                $app->make(LeParisienService::class),
                $app->make(LiberationService::class),
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
