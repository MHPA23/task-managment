<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prevent lazy loading of relationships
        Model::preventLazyLoading(! app()->isProduction());

        $this->app->bind(
            'App\Interface\CreateTaskActionInterface',
            'App\Actions\CreateTaskAction'
        );

        $this->app->bind(
            'App\Interface\GetTasksActionInterface',
            'App\Actions\GetTasksAction'
        );

        $this->app->bind(
            'App\Interface\GetTasksDashboardActionInterface',
            'App\Actions\GetTasksDashboardAction'
        );
    }
}
