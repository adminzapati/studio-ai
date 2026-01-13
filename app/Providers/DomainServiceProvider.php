<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\PromptRepositoryInterface;
use App\Data\Repositories\EloquentPromptRepository;

/**
 * Domain Service Provider
 * 
 * Binds Domain layer interfaces to Data layer implementations.
 * This is the IoC (Inversion of Control) configuration.
 */
class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(
            PromptRepositoryInterface::class,
            EloquentPromptRepository::class
        );

        // Add more bindings as needed:
        // $this->app->bind(ImageRepositoryInterface::class, EloquentImageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
