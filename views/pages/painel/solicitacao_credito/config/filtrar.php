<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;

$Painel = new PainelConfig\Filtrar('solicitacao_credito');

$Painel
    ->input(name: 'nome', titulo: 'Nome do usuário', label: 'Nome do usuário', placeholder: 'Nome do usuário')
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'operadora',
                lista: (new Operadora())->select('Escolha uma operadora'),
                titulo: 'Operadora',
                label: 'Operadora',
                placeholder: 'Operadora'
            )
            ->select(
                name: 'tipo',
                lista: (new Tipo())->select('Escolha um tipo'),
                titulo: 'Tipo',
                label: 'Tipo',
                placeholder: 'Tipo'
            );
    })
    /*->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Solicitado de',
                label: 'Solicitado de',
                placeholder: 'Solicitado de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Solicitado até',
                label: 'Solicitado até',
                placeholder: 'Solicitado até'
            );
    })*/
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
