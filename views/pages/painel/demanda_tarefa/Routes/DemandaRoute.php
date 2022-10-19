<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::nome('demanda')
    ::controller(Painel\Demanda\Controllers\DemandaController::class)
    ::grupo(function () {
        Route::nome('index')::view('/demanda');
        Route::nome('detalhe')::request(['!tipo'])::view('/demanda/{url}');
        Route
            ::nome('upload')
            ::request(['demanda', 'hash_validacao'])
            ::request(['arquivo'], 'files')
            ::post('/demanda/upload');

        Route::nome('arquivo')::request(['id', 'nome', 'hash_validacao'])::put('/demanda/arquivo');
        Route::nome('arquivo')::delete('/demanda/arquivo/{uuid}');

        Route::nome('seguir')::request(['id', 'acao', 'hash_validacao'])::post('/demanda/seguir');
        Route::nome('seguidores')::request(['id', '!seguidor', 'hash_validacao'])::post('/demanda/seguidores');

        Route::nome('arquivar')::request(['id', 'hash_validacao'])::put('/demanda/arquivar');

        Route::nome('salvar')::view('/demanda/salvar');
        Route::nome('salvar')::request([])::post('/demanda/salvar');

        Route::nome('mensagem')::request(['id', 'texto', 'hash_validacao'])::post('/demanda/mensagem');
    });
