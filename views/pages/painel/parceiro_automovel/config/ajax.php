<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('versao-salvar', function () use ($Painel) {
        $Painel
            ->request(['titulo', 'modelo', 'cor', 'valor_de', 'valor_por', 'status'])
            ->permissao('parceiro_automovel_add')
            ->scope('automovel_versao:salvar')
            ->metodo('post')
            ->rota('/automovel-versao');
    })
    ->grupo('versao-deletar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('parceiro_automovel_deletar')
            ->scope('automovel_versao:deletar')
            ->metodo('delete')
            ->rota('/automovel-versao/{id}');
    })
    ->grupo('versao-buscar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('parceiro_automovel_visualizar')
            ->scope('automovel_versao:buscar')
            ->metodo('get')
            ->rota('/automovel-versao/{id}');
    })
    ->grupo('versao-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'modelo', 'titulo', 'cor', 'valor_de', 'valor_por', 'status'])
            ->permissao('parceiro_automovel_editar')
            ->scope('automovel_versao:atualizar')
            ->metodo('post')
            ->rota('/automovel-versao/{id}');
    });
