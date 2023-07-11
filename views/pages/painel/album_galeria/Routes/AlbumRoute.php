<?php

use Route\Route;

Route::middleware(
    classe: App\Middlewares\Painel\AuthMiddleware::class,
    action: 'logado',
)::nome('album')::controller(Painel\AlbumGaleria\Controllers\AlbumController::class)::grupo(function () {
    Route::nome('index')::view('/album-galeria');
    Route::nome('add')::view('/album-galeria/add');
    Route::nome('deletar')::request(['hash_validacao', 'id'])::post('/album-galeria/deletar');
}, true)::nome('foto')::controller(Painel\AlbumGaleria\Controllers\FotoController::class)::grupo(function () {
    Route::nome('index')::view('/album-galeria/{uuid}');
});
