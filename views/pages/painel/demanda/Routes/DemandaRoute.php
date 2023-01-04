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
            ::nome('lista')
            ::view('/demanda');
        Route
            ::nome('detalhe')
            ::view('/demanda/detalhe/{id}');
        Route
            ::nome('add')
            ::view('/demanda/nova');
        Route
            ::nome('add')
            ::request([
                'tipo', '!titulo', 'empresa', '!dominio_tipo', '!dominio_link', '!login_api',
                '!login_link', '!app', '!texto',
            ])
            ::post('/demanda/nova');
    });
