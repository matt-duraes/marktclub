<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado'
    )
    ::nome('demanda')
    ::controller(Painel\Demanda\Controllers\DemandaController::class)
    ::grupo(function () {
        Route
            ::nome('tarefa')
            ::view('/demanda/tarefa');
        Route
            ::nome('novaTarefa')
            ::view('/demanda/nova-tarefa');
    });
