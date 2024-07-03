<?php

use PainelConfig\Filtrar;
use App\Classes\SiliumComissao\Status;

$Painel = new Filtrar('silium_comissao');

$Painel
    ->input(
        name: 'usuario',
        titulo: 'Nome do Usuário',
        label: 'Nome do Usuário',
        placeholder: 'Nome do Usuário'
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
    ->numero(
        name: 'quantidade',
        titulo: 'Quantidade',
        label: 'Quantidade',
        placeholder: 'Quantidade de Registros'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
