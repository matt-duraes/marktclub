<?php

use PainelConfig\Filtrar;
use App\Classes\Silium\StatusSaque;
use App\Classes\Silium\TipoConta;

$Painel = new Filtrar('silium_saque');

$Painel
    /*->input(
        name: 'usuario',
        titulo: 'Nome do Usuário',
        label: 'Nome do Usuário',
        placeholder: 'Nome do Usuário'
    )*/
    ->select(
        name: 'tipo_conta',
        lista: (new TipoConta())->select('Escolha um tipo de conta'),
        titulo: 'Tipo de Conta',
        label: 'Tipo de Conta',
        placeholder: 'Tipo de Conta'
    )
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
    ->select(
        name: 'status',
        lista: (new StatusSaque())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
