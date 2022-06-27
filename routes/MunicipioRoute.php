<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [App\Models\Municipio\LoginModel::class, 'relogarUsuario']
    )

    // Dashboard
    ::nome('dashboard')
    ::controller(App\Controllers\Municipio\IndexController::class)
    ::grupo(function () {
        Route::nome('index')::view('/');
    }, true)

    // Documento
    ::nome('documento')
    ::controller(App\Controllers\Municipio\DocumentoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::request(['!pesquisa', '!de', '!ate', '!tag'])
            ::view('/documentos');
        Route
            ::nome('filtro')
            ::request(['local'])
            ::view('/documento/filtro');
        Route
            ::nome('buscar')
            ::request(['!pesquisa', '!de', '!ate', '!tag', '!local'])
            ::view('/documento/buscar');
        Route
            ::nome('salvo')
            ::request(['!pesquisa', '!de', '!ate', '!tag', '!local'])
            ::view('/documento/salvos');
        Route
            ::nome('add')
            ::request(['hash_validacao', 'url'])
            ::post('/documento/add');
        Route
            ::nome('remover')
            ::request(['hash_validacao', 'url'])
            ::post('/documento/remover');
        Route
            ::nome('detalhe')
            ::view('/documento/{url}');
        Route
            ::nome('comentario')
            ::request(['url', 'citacao', 'mensagem', 'hash_validacao'])
            ::post('/documento/comentario');
        Route
            ::nome('download')
            ::view('/documento/download/{url}');
        Route
            ::nome('pdf')
            ::request(['url', '!texto', '!comentario', 'fonte'])
            ::post('/documento/pdf');
    }, true)

    // Emenda
    ::nome('emenda')
    ::controller(App\Controllers\Municipio\EmendaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::request(['!autor', '!ano', '!numero'])
            ::view('/emenda');
        Route
            ::nome('filtro')
            ::view('/emenda/filtro');
        Route
            ::nome('buscar')
            ::request(['!autor', '!ano', '!numero'])
            ::view('/emenda/buscar');
    }, true)

    // Sair
    ::nome('login')
    ::controller(App\Controllers\Municipio\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('sair')
            ::view('/sair');
    });

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'deslogado'
    )
    ::nome('login')
    ::controller(App\Controllers\Municipio\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login');
        Route
            ::nome('login')
            ::request(['hash_validacao_captcha', 'login', 'senha', 'logado'])
            ::post('/login');
    });
