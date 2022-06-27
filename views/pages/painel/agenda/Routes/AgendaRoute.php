<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::nome('agenda')
    ::controller(Painel\Agenda\Controllers\AgendaController::class)
    ::grupo(function () {
        Route::nome('index')::view('/agenda');

        Route::nome('buscar')::request(['data_inicial', 'data_final'])::post('/agenda/buscar');

        Route::nome('salvar')::request([
            'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
            'descricao', 'local', 'video', 'convidado'
        ])::post('/agenda/salvar');

        Route::nome('editar')::request([
            'id', 'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
            'descricao', 'local', 'video', 'convidado', 'notificar'
        ])::post('/agenda/editar');

        Route::nome('confirmar')::request(['id', 'confirmar'])::post('/agenda/confirmar');

        Route::nome('deletar')::request(['id'])::post('/agenda/deletar');
    });
