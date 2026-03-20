<?php

use App\Http\Controllers\Api\MotoristaApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Motorista PWA
|--------------------------------------------------------------------------
*/

// Login (sem autenticação)
Route::post('/motorista/login', [MotoristaApiController::class, 'login']);

// Rotas protegidas por Sanctum
Route::middleware('auth:sanctum')->prefix('motorista')->group(function () {
    Route::post('/logout', [MotoristaApiController::class, 'logout']);
    Route::get('/perfil', [MotoristaApiController::class, 'perfil']);
    Route::get('/coletas', [MotoristaApiController::class, 'coletas']);
    Route::get('/coletas/{coleta}', [MotoristaApiController::class, 'coletaDetalhe']);
    Route::post('/coletas/{coleta}/confirmar-chegada', [MotoristaApiController::class, 'confirmarChegada']);
    Route::post('/coletas/{coleta}/confirmar-entrega', [MotoristaApiController::class, 'confirmarEntrega']);
    Route::get('/disponiveis', [MotoristaApiController::class, 'disponiveis']);
    Route::get('/veiculos', [MotoristaApiController::class, 'veiculos']);
    Route::get('/destinos', [MotoristaApiController::class, 'destinos']);
    Route::post('/agendar', [MotoristaApiController::class, 'agendar']);
    Route::post('/coletas/{coleta}/cancelar', [MotoristaApiController::class, 'cancelar']);
    Route::post('/push-subscribe', [MotoristaApiController::class, 'pushSubscribe']);
    Route::delete('/push-unsubscribe', [MotoristaApiController::class, 'pushUnsubscribe']);
});
