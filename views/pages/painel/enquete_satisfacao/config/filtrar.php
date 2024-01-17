<?php

use App\Classes\EnqueteSatisfacao\Status;

$Painel = new PainelConfig\Filtrar('enquete_satisfacao');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\EnqueteSatisfacao\Helper::PERMISSAO_EMPRESA
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

$Painel->bloco(function () use ($Painel) {
    $Painel
        ->data(
            name: 'data_inicio',
            titulo: 'Criado em',
            label: 'Criado em'
        )
        ->data(
            name: 'data_fim',
            titulo: 'Criado até',
            label: 'Criado até'
        );
});

return $Painel;
