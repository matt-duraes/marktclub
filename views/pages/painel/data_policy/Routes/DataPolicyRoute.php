<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::nome('dataPolicy')
    ::controller(Painel\DataPolicy\Controllers\DataPolicyController::class)
    ::grupo(function () {
        Route
            ::action('index')
            ::view([
                '/proposicoes-seguidas',
                '/atos-do-executivo'
            ]);
        Route
            ::action('lista')
            ::request(['!pagina'])
            ::view([
                '/proposicoes-seguidas/lista/{hash}',
                '/atos-do-executivo/lista/{hash}'
            ]);
        Route
            ::action('proposicaoDetalhe')
            ::view('/proposicoes-seguidas/detalhe/{hash}');
        Route
            ::action('executivoDetalhe')
            ::view('/atos-do-executivo/detalhe/{hash}');
    });
