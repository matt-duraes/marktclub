<?php

use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Tipo;
use Helpers\ApiHelper;

$Painel = new PainelConfig\Filtrar('parceiro_automovel');

$Loja = (new ApiHelper(token: true))
    ->json([
        'titulo' => 'Escolha um parceiro',
        'tipo'   => Tipo::AUTOMOVEL
    ])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel
    ->select(
        name: 'parceiro',
        lista: $Loja,
        titulo: 'Parceiro',
        label: 'Parceiro',
        placeholder: 'Parceiro'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Data de Inicío',
                label: 'Data de Inicío',
                placeholder: 'Data de Inicío'
            )
            ->data(
                name: 'data_final',
                titulo: 'Data Final',
                label: 'Data Final',
                placeholder: 'Data Final'
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
