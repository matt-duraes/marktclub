<?php

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Filtrar('solicitacao_loja');

$Painel
    ->input(name: 'nome', titulo: 'Nome indicação', label: 'Nome indicação', placeholder: 'Nome indicação')
    ->bloco(function () use ($Painel) {
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
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                titulo: 'Status',
                label: 'Status',
                placeholder: 'Status'
            );
    });

return $Painel;
