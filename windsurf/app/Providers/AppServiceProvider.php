<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Produto;
use App\Observers\ProdutoObserver;

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
        // Bypass de permissões em desenvolvimento
        if (config('permissions.bypass')) {
            Gate::before(fn ($user, $ability) => true);
        }

        // Registrar Observer de Produto
        Produto::observe(ProdutoObserver::class);
    }
}
