<?php

namespace App\Providers;

use App\Application\Contracts\ApplianceRepositoryInterface;
use App\Infrastructure\Repositories\EloquentApplianceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApplianceRepositoryInterface::class, EloquentApplianceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
