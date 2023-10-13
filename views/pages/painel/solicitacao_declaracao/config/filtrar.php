<?php

use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Filtrar('solicitacao_declaracao');

$Painel
    ->input(
        name: 'titulo',
        titulo: 'Nome do parceiro',
        label: 'Nome do parceiro',
        placeholder: 'Nome do parceiro'
    )
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
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
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Empresa',
        placeholder: 'Status'
    );

return $Painel;
