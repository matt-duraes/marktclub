<?php

use PainelConfig\Filtrar;
use App\Classes\Silium\StatusComissao;

$Painel = new Filtrar('silium_comissao');

$Painel
    ->input(
        name: 'usuario',
        titulo: 'Nome do Usuário',
        label: 'Nome do Usuário',
        placeholder: 'Nome do Usuário'
    )
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Comissão de',
                label: 'Comissão de',
                placeholder: 'Comissão de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Comissão até',
                label: 'Comissão até',
                placeholder: 'Comissão até'
            );
    })
    ->select(
        name: 'status',
        lista: (new StatusComissao())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
