<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('votacao-cancelar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_editar')
            ->metodo('post')
            ->rota('/votacao-dado/cancelar');
    })
    ->grupo('votacao-bloquear', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_editar')
            ->metodo('post')
            ->rota('/votacao-dado/bloquear');
    })
    ->grupo('votacao-resultado', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_resultado')
            ->metodo('post')
            ->rota('/votacao-dado/resultado');
    })
    ->grupo('pergunta-listar', function () use ($Painel) {
        $Painel
            ->request(['votacao', 'pagina', 'quantidade'])
            ->permissao('votacao_visualizar')
            ->metodo('get')
            ->rota('/votacao-pergunta');
    })
    ->grupo('pergunta-buscar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_visualizar')
            ->metodo('get')
            ->rota('/votacao-pergunta/{id}');
    })
    ->grupo('pergunta-salvar', function () use ($Painel) {
        $Painel
            ->request(['votacao', 'titulo', 'texto', 'tipo', 'pode_nulo'])
            ->permissao('votacao_visualizar')
            ->metodo('post')
            ->rota('/votacao-pergunta');
    })
    ->grupo('pergunta-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'titulo', 'texto', 'tipo', 'pode_nulo'])
            ->permissao('votacao_visualizar')
            ->metodo('put')
            ->rota('/votacao-pergunta/{id}');
    })
    ->grupo('pergunta-ordenar', function () use ($Painel) {
        $Painel
            ->request(['pagina', 'quantidade', 'id'])
            ->permissao('votacao_visualizar')
            ->metodo('put')
            ->rota('/votacao-pergunta/ordenar');
    })
    ->grupo('pergunta-deletar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_visualizar')
            ->metodo('delete')
            ->rota('/votacao-pergunta/{id}');
    })
    ->grupo('resposta-listar', function () use ($Painel) {
        $Painel
            ->request(['pergunta', 'pagina', 'quantidade'])
            ->permissao('votacao_visualizar')
            ->metodo('get')
            ->rota('/votacao-resposta');
    })
    ->grupo('resposta-buscar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_visualizar')
            ->metodo('get')
            ->rota('/votacao-resposta/{id}');
    })
    ->grupo('resposta-salvar', function () use ($Painel) {
        $Painel
            ->request(['pergunta', 'titulo', 'texto', 'escrever_voto', 'voto_nulo'])
            ->permissao('votacao_visualizar')
            ->metodo('post')
            ->rota('/votacao-resposta');
    })
    ->grupo('resposta-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'titulo', 'texto', 'escrever_voto', 'voto_nulo'])
            ->permissao('votacao_visualizar')
            ->metodo('put')
            ->rota('/votacao-resposta/{id}');
    })
    ->grupo('resposta-ordenar', function () use ($Painel) {
        $Painel
            ->request(['pagina', 'quantidade', 'id'])
            ->permissao('votacao_visualizar')
            ->metodo('put')
            ->rota('/votacao-resposta/ordenar');
    })
    ->grupo('resposta-deletar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('votacao_visualizar')
            ->metodo('delete')
            ->rota('/votacao-resposta/{id}');
    });
