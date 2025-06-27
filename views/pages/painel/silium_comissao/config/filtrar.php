<?php

use PainelConfig\Filtrar;
use App\Classes\SiliumComissao\Status;

$Painel = new Filtrar('silium_comissao');

$Painel
    ->input(
        name: 'cliente',
        titulo: 'Nome do Usuário',
        label: 'Nome do Usuário',
        placeholder: 'Nome do Usuário'
    )
    ->input(
        name: 'parceiro',
        titulo: 'Nome da Loja',
        label: 'Nome da Loja',
        placeholder: 'Nome da Loja'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Compra de',
                label: 'Compra de',
                placeholder: 'Compra de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Compra até',
                label: 'Compra até',
                placeholder: 'Compra até'
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
