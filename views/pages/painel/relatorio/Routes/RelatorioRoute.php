<?php

use Route\Route;
use Painel\Relatorio\Controllers\RelatorioController;

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )
    ::middleware(
        classe: App\Middlewares\Painel\PermissaoMiddleware::class,
        action: 'validar',
        parametro: ['relatorio_acesso_index']
    )
    ::controller(RelatorioController::class)
    ::nome('relatorio_acesso')
    ::grupo(function () {
        Route
            ::action('acesso')
            ::view('/relatorio/acesso');

        Route
            ::nome('acessoDia')
            ::request(['de', 'ate', '!empresa'])
            ::get('/relatorio/acesso-dia');

        Route
            ::nome('maisAcessado')
            ::request(['de', 'ate', 'local', '!empresa'])
            ::get('/relatorio/mais-acessado');

        Route
            ::nome('dispositivo')
            ::request(['de', 'ate', 'tipo', '!empresa'])
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
    ::controller(RelatorioController::class)
    ::nome('relatorio_usuario')
    ::grupo(function () {
        Route
            ::nome('usuario')
            ::view('/relatorio/usuario');

        Route
            ::nome('dadoUsuario')
            ::request(['!empresa'])
            ::get('/relatorio/dado-usuario');
    });

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::controller(RelatorioController::class)
    ::nome('relatorio_venda')
    ::grupo(function () {
        Route
            ::nome('lojaVenda')
            ::view('/relatorio/loja-venda');

        Route
            ::nome('lojaVendaBuscar')
            ::request(['de', 'ate', '!empresa'])
            ::get('/relatorio/loja-venda-buscar');
    });
