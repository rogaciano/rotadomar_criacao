<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Rotas de autenticação já incluídas no web.php via require
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            // Verificar se o erro é de conexão (SQLSTATE[HY000] [2002])
            if (str_contains($e->getMessage(), '1045') || str_contains($e->getMessage(), '2002')) {
                return response()->view('errors.db-connection', [], 500);
            }
            return null;
        });
    })->create();
