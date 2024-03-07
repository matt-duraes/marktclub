<?php

use App\Classes\ConstrutorClube\Helper;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar('construtor-clube');

$Painel
    ->input(
        name: 'titulo_clube',
        titulo: 'Clube',
        label: 'Nome do clube',
        placeholder: 'Nome do clube'
    )
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Criado em',
                label: 'Criado em'
            )
            ->data(
                name: 'data_final',
                titulo: 'Criado até',
                label: 'Criado até'
            );
    })
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
