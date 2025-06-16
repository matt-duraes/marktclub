<?php

use App\Classes\ComercialPopup\Status;
use PainelConfig\Filtrar;

$Painel = new Filtrar('usuario_cliente_codigo');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: 'usuario_cliente_codigo_empresa'
    )
    ->select(
        name: 'subempresa',
        lista: 'subempresa',
        titulo: 'Subempresa',
        label: 'Subempresa',
        placeholder: 'Subempresa',
        permissao: 'usuario_cliente_codigo_subempresa',
        todasSubempresa:  true
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Data Início',
                label: 'Data de início',
                placeholder: 'Data de início'
            )
            ->data(
                name: 'data_final',
                titulo: 'Data Final',
                label: 'Data final',
                placeholder: 'Data final'
            );
    })
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status'
    );

return $Painel;
