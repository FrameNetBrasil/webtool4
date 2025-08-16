<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        View::addExtension('js', 'php');
        Blade::anonymousComponentPath(app_path('UI/layout'), 'layout');
        Blade::anonymousComponentPath(app_path('UI/components'), 'ui');
    }
}
