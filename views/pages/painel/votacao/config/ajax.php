<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('pergunta-listar', function () use ($Painel) {
        $Painel
            ->request(['votacao', 'pagina', 'quantidade'])
            ->permissao('votaca_visualizar')
            ->metodo('get')
            ->rota('/votacao-pergunta');
    })
    ->grupo('pergunta-salvar', function () use ($Painel) {
        $Painel
            ->request(['votacao', 'titulo', 'texto', 'tipo'])
            ->permissao('votaca_visualizar')
            ->metodo('get')
            ->rota('/votacao-pergunta');
    })
    ->grupo('pergunta', function () use ($Painel) {
        $Painel
            ->request(['votacao'])
            ->permissao('votaca_visualizar')
            ->metodo('get')
            ->rota('/votacao-pergunta');
    })
    ->grupo('resposta', function () use ($Painel) {
        $Painel
            ->request(['pergunta'])
            ->permissao('votacao_visualizar')
            ->metodo('get')
            ->rota('/votacao-resposta');
    });
