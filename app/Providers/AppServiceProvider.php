<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Pagination\Paginator; // dé-commente si tu passes à Bootstrap

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
        // 1) Forcer HTTPS en production (si derrière un proxy/SSL)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 2) Aides dev : détecter les N+1 et accès douteux en local
        if ($this->app->environment('local')) {
            Model::preventLazyLoading();
            // Optionnel (si ta version de Laravel le supporte) :
            // Model::shouldBeStrict();
            // Model::preventSilentlyDiscardingAttributes();
        }

        // 3) Directive Blade pour l'admin : @admin ... @endadmin
        Blade::if('admin', fn () => auth()->check() && auth()->user()->isAdmin());

        // 4) Pagination : si tu utilises Bootstrap au lieu de Tailwind (Breeze utilise Tailwind par défaut)
        // Paginator::useBootstrapFive();
    }
}
