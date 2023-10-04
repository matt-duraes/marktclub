<?php

use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Filtrar('solicitacao_loja');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Nome')
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'origem',
                lista: (new Origem())->select('Escolha uma origem'),
                titulo: 'Origem',
                label: 'Origem',
                placeholder: 'Origem'
            )
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                titulo: 'Status',
                label: 'Status',
                placeholder: 'Status'
            );
    });

return $Painel;
