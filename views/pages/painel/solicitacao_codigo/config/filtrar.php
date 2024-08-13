<?php

use App\Classes\SolicitacaoCodigo\Status;
use PainelConfig\Filtrar;

$Painel = new Filtrar('solicitacao_codigo');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: 'solicitacao_codigo_empresa'
    )
    ->select(
        name: 'parceiro',
        lista: [
            'a' => 'a',
            'b' => 'b'
        ],
        titulo: 'Parceiro',
        label: 'Parceiro',
        placeholder: 'Parceiro'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_emissao',
                titulo: 'Data Emissão',
                label: 'Data Emissão',
                placeholder: 'Data Emissão'
            )
            ->data(
                name: 'data_vencimento',
                titulo: 'Data Vencimento',
                label: 'Data Vencimento',
                placeholder: 'Data Vencimento'
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
