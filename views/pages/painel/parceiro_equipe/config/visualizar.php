<?php

use PainelConfig\Visualizar;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Categoria;

$Painel = new Visualizar('parceiro_equipe');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo_interno', 'Título')
            ->linha('categoria_principal', 'Categoria')
            ->e('endereco_estado', 'Estado')
            ->linha('status', 'Status');
    });
    $Painel->bloco(titulo: 'Ver parceiro', callback: function () use ($Painel) {
        $Painel->botao(
            'id',
            'Parceiro',
            link: LINK . '/app/visualizar/parceiro-loja/{id}',
            target: Visualizar::TARGET_BLANK
        );
    });
});

$Painel
        ->botaoDestaque(
            texto: 'Adicionar captador',
            cor: 'verde'
        );

$Painel
    ->replace('categoria_principal', (new Categoria())->select())
    ->replace('status', (new Status())->select());

return $Painel;
