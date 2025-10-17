<?php

namespace App\Domain\Press\Providers;

use App\Application\Press\PressOrchestrator;
use App\Domain\Press\Services\{SourceServices\LeMondeService,
    SourceServices\LeParisienService,
    SourceServices\LequipeService,
    SourceServices\LiberationService};
use Illuminate\Support\ServiceProvider;

class PressServiceProvider extends ServiceProvider
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
