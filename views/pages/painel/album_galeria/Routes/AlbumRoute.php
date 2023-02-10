<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )
    ::nome('album')
    ::controller(Painel\AlbumGaleria\Controllers\AlbumController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/album-galeria');
        Route
            ::nome('add')
            ::view('/album-galeria/add');
        Route
            ::nome('deletar')
            ::request(['hash_validacao', 'id'])
            ::post('/album-galeria/deletar');
    }, true)

    ::nome('foto')
    ::controller(Painel\AlbumGaleria\Controllers\FotoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/album-galeria/{uuid}');
        // Route
        //     ::nome('upload')
        //     ::request(['id', 'capa', 'hash_validacao'])
        //     ::request(['arquivo'], 'files')
        //     ::post('/album/galeria-upload');
        // Route::nome('imagem')::request(['id', 'pagina', 'hash_validacao'])::post('/album/galeria-imagem');
        // Route::nome('download')::view('/album/galeria-download/{id}');
        // Route::nome('editar')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-editar');
        // Route::nome('editar')::request(['id', 'album', 'titulo', 'capa', 'hash_validacao'])::put('/album/galeria-editar');
        // Route::nome('deletar')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-deletar');
        // Route::nome('ordem')::request(['id', 'album', 'hash_validacao'])::post('/album/galeria-ordem');
    });
