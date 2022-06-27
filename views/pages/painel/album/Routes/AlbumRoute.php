<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::nome('album')
    ::controller(Painel\Album\Controllers\AlbumController::class)
    ::grupo(function () {
        Route::nome('index')::view('/album');
        Route::nome('add')::view('/album/add');
        Route::nome('deletar')::request(['hash_validacao', 'id'])::post('/album/deletar');
    }, true)

    ::nome('galeria')
    ::controller(Painel\Album\Controllers\GaleriaController::class)
    ::grupo(function () {
        Route::nome('index')::view('/album/galeria/{uuid}');
        Route
            ::nome('upload')
            ::request(['id', 'capa', 'hash_validacao'])
            ::request(['arquivo'], 'files')
            ::post('/album/galeria-upload');
        Route::nome('imagem')::request(['id', 'pagina', 'hash_validacao'])::post('/album/galeria-imagem');
        Route::nome('download')::view('/album/galeria-download/{id}');
        Route::nome('editar')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-editar');
        Route::nome('editar')::request(['id', 'album', 'titulo', 'capa', 'hash_validacao'])::put('/album/galeria-editar');
        Route::nome('deletar')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-deletar');
        Route::nome('ordem')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-ordem');
    });
