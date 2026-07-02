<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações da API de Estoque de Tecidos
    |--------------------------------------------------------------------------
    |
    | Estas configurações são usadas para conectar à API externa de estoque
    | de tecidos. Os valores são obtidos do arquivo .env
    |
    */

    'api_url' => env('ESTOQUE_API_URL', ''),
    'empresa' => env('ESTOQUE_API_EMPRESA', ''),
    'token' => env('ESTOQUE_API_TOKEN', ''),
    'armazenador' => env('ESTOQUE_API_ARMAZENADOR', ''),
];
