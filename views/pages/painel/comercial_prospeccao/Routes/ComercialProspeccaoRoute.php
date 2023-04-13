<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )
    ::nome('comercialProspeccao')
    ::controller(Painel\ComercialProspeccao\Controllers\ComercialProspeccaoController::class)
    ::grupo(function () {
        Route
            ::nome('lista')
            ::view('/comercial-prospeccao');
    });
