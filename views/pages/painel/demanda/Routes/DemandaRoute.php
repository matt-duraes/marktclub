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
            ::nome('demanda')
            ::view('/demanda/demanda/{id}');
        Route
            ::nome('tarefaEditar')
            ::view('/demanda/tarefa-editar/{id}/{demanda}');
        Route
            ::nome('demandaSalvar')
            ::view('/demanda/demanda-salvar');
        Route
            ::nome('demandaEditar')
            ::view('/demanda/demanda-editar/{id}');

        Route
            ::nome('tarefaEditar')
            ::request(['titulo', 'texto', 'tipo', 'hora'])
            ::post('/demanda/tarefa-editar/{id}');
        Route
            ::nome('tarefaArquivo')
            ::request(['arquivo'])
            ::post('/demanda/tarefa-arquivo/{id}');
        Route
            ::nome('tarefa')
            ::delete('/demanda/tarefa/{id}');
        Route
            ::nome('demandaSalvar')
            ::request([
                'tipo', '!titulo', 'empresa', '!dominio_tipo', '!dominio_link', '!login_api',
                '!login_link', '!app', '!texto',
            ])
            ::post('/demanda/demanda-salvar');
    });
