<?php

use Route\Route;

Route
    ::nome('tabela_salvar')
    ::middleware(classe: App\Middlewares\Painel\AuthMiddleware::class, action: 'logado',)
    ::middleware(App\Middlewares\Painel\PermissaoMiddleware::class, 'validar', ['tabela_usuario_salvar'])
    ::controller(Painel\UsuarioTabela\Controllers\TabelaController::class)
    ::grupo(function () {
        Route
            ::action('salvar')
            ::view('/tabela/salvar');
        Route
            ::action('salvar')
            ::request(['tipo', 'nome'])
            ::request(['arquivo'], 'files')
            ::post('/tabela');
        Route
            ::action('analisar')
            ::request(['tipo'])
            ::request(['arquivo'], 'files')
            ::post('/tabela/analisar');
    });
