<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\TipoLoja;

$Painel = new PainelConfig\Filtrar('parceiro_automovel');

$Loja = (new ApiHelper(token: true))
    ->json([
        'titulo' => 'Escolha um parceiro',
        'tipo'   => TipoLoja::AUTOMOVEL
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
    ->input(
        name: 'titulo',
        titulo: 'Nome do modelo',
        label: 'Nome do modelo',
        placeholder: 'Nome do modelo'
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
