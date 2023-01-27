<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::middleware(
        classe: App\Middlewares\Painel\PermissaoMiddleware::class,
        action: 'validar',
        parametro: ['relatorio_acesso_index']
    )
    ::controller(Painel\Relatorio\Controllers\RelatorioController::class)
    ::nome('relatorio_acesso')
    ::grupo(function () {
        Route
            ::action('acesso')
            ::view('/relatorio/acesso');

        Route
            ::nome('usuarioAcesso')
            ::request(['de', 'ate'])
            ::get('/relatorio/usuario-acesso');

        Route
            ::nome('maisAcessado')
            ::request(['de', 'ate', 'local'])
            ::get('/relatorio/mais-acessado');

        Route
            ::nome('dispositivo')
            ::request(['de', 'ate', 'tipo'])
            ::get('/relatorio/dispositivo');
    });

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::middleware(
        classe: App\Middlewares\Painel\PermissaoMiddleware::class,
        action: 'validar',
        parametro: ['relatorio_usuario_index']
    )
    ::controller(Painel\Relatorio\Controllers\RelatorioController::class)
    ::nome('relatorio_usuario')
    ::grupo(function () {
        Route
            ::nome('usuario')
            ::view('/relatorio/usuario');

        Route
            ::nome('usuarioBuscar')
            ::get('/relatorio/usuario-buscar');
    });
