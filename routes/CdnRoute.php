<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;

Route
    ::nome('analytics')
    ::controller(App\Controllers\Cdn\AnalyticsController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('analytics')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:listar'])
            ::request(['!pagina', '!quantidade', '!usuario', '!de', '!ate'], 'json')
            ::request(['!de', '!ate'], 'get')
            ::get('/relatorio/analytics');
    });
