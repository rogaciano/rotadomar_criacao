<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Produto;
use App\Observers\ProdutoObserver;
use App\Http\View\Composers\NotificationComposer;
use Carbon\Carbon;

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
        // Configurar Carbon para português brasileiro
        Carbon::setLocale('pt_BR');
        setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil');
        
        // Bypass de permissões em desenvolvimento
        if (config('permissions.bypass')) {
            Gate::before(fn ($user, $ability) => true);
        }

        // Registrar Observer de Produto
        Produto::observe(ProdutoObserver::class);
        
        // Registrar View Composer para notificações de movimentações
        // DESABILITADO - Sistema de notificações muito pesado
        // View::composer('layouts.navigation', NotificationComposer::class);
    }
}
