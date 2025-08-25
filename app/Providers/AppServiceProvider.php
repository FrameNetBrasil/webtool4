<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config("webtool.logSQL") == 'debug') {
            DB::enableQueryLog();
            DB::listen(function ($query) {
                debugQuery($query->sql, $query->bindings);
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addExtension('js','php');
    }
}
