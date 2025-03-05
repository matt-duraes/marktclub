<?php

use Route\Route;

Route
    ::middleware(App\Middlewares\Painel\AuthMiddleware::class, 'logado')
    ::controller(Painel\Ranking\Controllers\RankingController::class)
    ::nome('ranking_indicacao')
    ::grupo(function () {
        Route
            ::action('ranking')
            ::view('/ranking');
    });
