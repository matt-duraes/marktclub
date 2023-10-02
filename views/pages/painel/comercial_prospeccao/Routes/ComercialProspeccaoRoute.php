<?php

use Route\Route;

Route
    ::middleware(classe: App\Middlewares\Painel\AuthMiddleware::class, action: 'logado')
    ::nome('comercialProspeccao')
    ::controller(Painel\ComercialProspeccao\Controllers\ComercialProspeccaoController::class)
    ::grupo(function () {
        Route
            ::nome('lista')
            ::view('/comercial-prospeccao');
        Route
            ::nome('contato')
            ::view('/comercial-prospeccao/contato/{id}');
        Route
            ::nome('atualizarStatus')
            ::request(['status', 'id', '!motivo_standby'])
            ::post('/comercial-prospeccao/atualizar-status');
        Route
            ::nome('atualizarProspeccao')
            ::request(['prospeccao', 'id'])
            ::post('/comercial-prospeccao/atualizar-prospeccao');
    });
