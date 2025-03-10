<?php

use Route\Route;

Route
    ::middleware(App\Middlewares\Painel\AuthMiddleware::class, 'logado')
    ::controller(Painel\Dashboard\Controllers\DashboardController::class)
    ::nome('dashboard')
    ::grupo(function () {
        Route
            ::action('dashboard')
            ::view('/dashboard');
    });
