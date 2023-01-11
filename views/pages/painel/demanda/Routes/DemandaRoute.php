<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado'
    )
    ::nome('demanda')
    ::controller(Painel\Demanda\Controllers\DemandaController::class)
    ::grupo(function () {
        Route
            ::nome('lista')
            ::view('/demanda');

        Route
            ::nome('tarefa')
            ::view('/demanda/tarefa/{id}');
        Route
            ::nome('tarefaEditar')
            ::view('/demanda/tarefa-editar/{id}/{demanda}');
        Route
            ::nome('tarefaEditar')
            ::request(['titulo', 'texto', 'tipo'])
            ::post('/demanda/tarefa-editar/{id}');

        Route
            ::nome('tarefa')
            ::delete('/demanda/tarefa/{id}');

        Route
            ::nome('add')
            ::view('/demanda/nova');
        Route
            ::nome('add')
            ::request([
                'tipo', '!titulo', 'empresa', '!dominio_tipo', '!dominio_link', '!login_api',
                '!login_link', '!app', '!texto',
            ])
            ::post('/demanda/nova');
    });
