<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('versao-salvar', function () use ($Painel) {
        $Painel
            ->request(['titulo', 'imagem','modelo', 'cor', 'valor_de', 'valor_por', 'status'])
            ->permissao('parceiro_automovel_add')
            ->metodo('post')
            ->rota('/automovel-versao');
    })
    ->grupo('versao-deletar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('parceiro_automovel_deletar')
            ->metodo('delete')
            ->rota('/automovel-versao/{id}');
    })
    ->grupo('versao-buscar', function () use ($Painel) {
        $Painel
            ->request(['id'])
            ->permissao('parceiro_automovel_visualizar')
            ->metodo('get')
            ->rota('/automovel-versao/{id}');
    })
    ->grupo('versao-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'titulo', 'imagem', 'cor', 'valor_de', 'valor_por', 'status'])
            ->permissao('parceiro_automovel_editar')
            ->metodo('put')
            ->rota('/automovel-versao/{id}');
    });
