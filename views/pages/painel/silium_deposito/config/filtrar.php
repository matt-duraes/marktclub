<?php

use App\Classes\Silium\StatusDeposito;

$Painel = new PainelConfig\Filtrar('silium_deposito');

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
                titulo: 'Depositado de',
                label: 'Depositado de',
                placeholder: 'Depositado de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Depositado até',
                label: 'Depositado até',
                placeholder: 'Depositado até'
            );
    })
    ->select(
        name: 'status',
        lista: (new StatusDeposito())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
