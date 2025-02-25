<?php

use Route\Route;

Route
    ::middleware(classe: App\Middlewares\Painel\AuthMiddleware::class, action: 'logado')
    ::controller(Painel\Ranking\Controllers\RankingController::class)
    ::nome('ranking_indicacao')
    ::grupo(function () {
        Route
            ::action('ranking')
            ::view('/ranking');
    });
