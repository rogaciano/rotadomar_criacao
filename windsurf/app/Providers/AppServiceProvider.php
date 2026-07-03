<?php

namespace App\Providers;

use App\Policies\ProdutoPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Produto;
use App\Models\UserPermission;
use App\Models\Group;
use App\Observers\ProdutoObserver;
use App\Observers\UserPermissionObserver;
use App\Observers\GroupObserver;
use App\Http\View\Composers\NotificationComposer;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope é dependência de dev: só registra em ambiente local
        // (permite composer install --no-dev em produção)
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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

        Gate::policy(Produto::class, ProdutoPolicy::class);

        // Registrar Observers
        Produto::observe(ProdutoObserver::class);
        UserPermission::observe(UserPermissionObserver::class);
        Group::observe(GroupObserver::class);

        // Registrar View Composer para notificações de movimentações
        // DESABILITADO - Sistema de notificações muito pesado
        // View::composer('layouts.navigation', NotificationComposer::class);
    }
}
