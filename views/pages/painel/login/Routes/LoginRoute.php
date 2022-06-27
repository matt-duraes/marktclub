<?php

use Route\Route;
use App\Middlewares\AuthMiddleware;
use Painel\Login\Controllers\LoginController;

Route
    ::middleware(
        classe: AuthMiddleware::class,
        action: 'deslogado',
    )
    ::controller(LoginController::class)
    ::nome('login')
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login');

        Route
            ::nome('login')
            ::request(['hash_validacao_captcha', 'login', 'senha', 'logado'])
            ::post('/login');

        Route
            ::nome('social')
            ::request(['hash_validacao', 'id', 'token', 'tipo'])
            ::post('/login/social');

        Route
            ::nome('desbloquear')
            ::request(['hash_validacao', 'login', 'senha', 'logado'])
            ::post('/login/desbloquear');
    });

Route
    ::middleware(
        classe: AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::controller(LoginController::class)
    ::nome('login')
    ::grupo(function () {
        Route
            ::action('bloquear')
            ::view('/login/bloquear');

        Route
            ::nome('sair')
            ::view('/sair');
    });
