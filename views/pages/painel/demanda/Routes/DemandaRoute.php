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
            ::nome('demandaSalvar')
            ::view('/demanda/demanda-salvar');
        Route
            ::nome('tarefaSalvar')
            ::view('/demanda/tarefa-salvar/{demanda}');
        Route
            ::nome('tarefaEditar')
            ::view('/demanda/tarefa-editar/{id}/{demanda}');

        Route
            ::nome('demandaSalvar')
            ::request([
                'tipo', '!titulo', 'empresa', '!dominio_tipo', '!dominio_link', '!login_api',
                '!login_link', '!app', '!texto', '!cdn'
            ])
            ::post('/demanda/demanda-salvar');
        Route
            ::nome('demandaEditar')
            ::view('/demanda/demanda-editar/{id}');
        Route
            ::nome('demandaEditar')
            ::request(['titulo', 'dono', 'empresa', 'com_prazo', 'data_entrega'])
            ::post('/demanda/demanda-editar/{id}');
        Route
            ::nome('demandaLiberar')
            ::post('/demanda/demanda-liberar/{id}');

        Route
            ::nome('tarefaEditar')
            ::request(['titulo', 'texto', 'tipo', 'minuto'])
            ::post('/demanda/tarefa-editar/{id}');
        Route
            ::nome('tarefaSalvar')
            ::request(['demanda', 'titulo', 'texto', 'tipo', 'minuto'])
            ::post('/demanda/tarefa-salvar');
        Route
            ::nome('tarefaArquivo')
            ::request(['arquivo'])
            ::post('/demanda/tarefa-arquivo/{id}');
        Route
            ::nome('tarefa')
            ::delete('/demanda/tarefa/{id}');
        Route
            ::nome('tarefaLike')
            ::post('/demanda/tarefa-like/{id}');
        Route
            ::nome('tarefaDeslike')
            ::request(['motivo'])
            ::post('/demanda/tarefa-deslike/{id}');

        Route
            ::nome('trabalhoComecar')
            ::get('/demanda/trabalho-comecar/{tarefa}/{demanda}');
        Route
            ::nome('trabalhoAtualizar')
            ::get('/demanda/trabalho-atualizar/{id}');
        Route
            ::nome('trabalhoParar')
            ::get('/demanda/trabalho-parar/{id}');
        Route
            ::nome('trabalhoConcluir')
            ::get('/demanda/trabalho-concluir/{id}');
    });
