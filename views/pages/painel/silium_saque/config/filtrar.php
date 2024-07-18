<?php

use PainelConfig\Filtrar;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoResgate;

$Painel = new Filtrar('silium_saque');

$TipoConta = new TipoConta();
$Status = new Status();
$TipoResgate = new TipoResgate();

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
    ->bloco(function () use ($Painel, $TipoConta, $TipoResgate) {
        $Painel
            ->select(
                name: 'tipo_conta',
                lista: $TipoConta->select('Escolha um tipo de conta'),
                titulo: 'Tipo de Conta',
                label: 'Tipo de Conta',
                placeholder: 'Tipo de Conta'
            )
            ->select(
                name: 'tipo_resgate',
                lista: $TipoResgate->select('Escolha um tipo de resgate'),
                titulo: 'Tipo de Resgate',
                label: 'Tipo de Resgate',
                placeholder: 'Tipo de Resgate'
            );
    })
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->numero(
                name: 'quantidade',
                titulo: 'Quantidade',
                label: 'Quantidade',
                placeholder: 'Quantidade de Registros'
            )
            ->select(
                name: 'status',
                lista: $Status->select('Escolha um status'),
                titulo: 'Status',
                label: 'Status',
                placeholder: 'Status'
            );
    });

return $Painel;
