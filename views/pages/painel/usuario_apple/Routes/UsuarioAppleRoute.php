<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )
    ::middleware(
        App\Middlewares\Painel\PermissaoMiddleware::class,
        'validar',
        ['usuario_cliente_apple']
    )
    ::controller(Painel\UsuarioApple\Controllers\AppleController::class)
    ::nome('tabela_salvar')
    ::grupo(function () {
        Route::nome('index')::view('/usuario-apple');
        Route::nome('salvar')::post('/usuario-apple');
    });

