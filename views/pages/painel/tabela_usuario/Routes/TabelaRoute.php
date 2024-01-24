<?php

use Route\Route;

Route
    ::nome('tabela_salvar')
    ::middleware(classe: App\Middlewares\Painel\AuthMiddleware::class, action: 'logado',)
    ::middleware(App\Middlewares\Painel\PermissaoMiddleware::class, 'validar', ['tabela_usuario_salvar'])
    ::controller(Painel\TabelaUsuario\Controllers\TabelaController::class)
    ::grupo(function () {
        Route
            ::action('salvar')
            ::view('/tabela/salvar');
        Route
            ::action('bloquear')
            ::view('/tabela/bloquear');
        Route
            ::action('historico')
            ::view('/tabela/historico');
        Route
            ::action('salvar')
            ::request(['tipo'])
            ::request(['arquivo'], 'files')
            ::post('/tabela/salvar');
        Route
            ::action('analisar')
            ::request(['tipo'])
            ::request(['arquivo'], 'files')
            ::post('/tabela/analisar');
    });
