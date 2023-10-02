<?php

use App\Classes\ComunicacaoContato\Status;

$Painel = new PainelConfig\Filtrar('comunicacao_contato');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Data Criação De', label: 'Data Criação De')
            ->data(name: 'data_criacao_ate', titulo: 'Data Criação Até', label: 'Data Criação Até');
    })
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status'
    );

$Painel->replace('status', (new Status())->select());

return $Painel;
