<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
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
        // helps identify n+1 query issues during development
        Model::preventLazyLoading(!app()->isProduction());

        if (app()->isProduction() && true === env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
