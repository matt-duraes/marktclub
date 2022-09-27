<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::controller(Painel\Historico\Route\Controller::class)
    ::nome('historico')
    ::grupo(callback: function () {
        Route
            ::nome('index')
            ::request(['!location'])
            ::post('/historico');

        Route
            ::nome('salvar')
            ::request(['id', 'texto'])
            ::put('/historico');
    });
