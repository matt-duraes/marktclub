<?php

use App\Classes\SolicitacaoLoja\Status;
use PainelConfig\Filtrar;

$Painel = new Filtrar('solicitacao_loja');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: 'solicitacao_loja_empresa'
    )
    ->input(
        name: 'usuario',
        titulo: 'Nome Usuário',
        label: 'Nome Usuário',
        placeholder: 'Nome Usuário'
    )
    ->input(
        name: 'parceiro',
        titulo: 'Nome Indicação',
        label: 'Nome Indicação',
        placeholder: 'Nome Indicação'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'indicacao_inicio',
                titulo: 'Indicado de',
                label: 'Indicado de',
                placeholder: 'Indicado de'
            )
            ->data(
                name: 'indicacao_final',
                titulo: 'Indicado até',
                label: 'Indicado até',
                placeholder: 'Indicado até'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'prospeccao_inicio',
                titulo: 'Prospectado de',
                label: 'Prospectado de',
                placeholder: 'Prospectado de'
            )
            ->data(
                name: 'prospeccao_final',
                titulo: 'Prospectado até',
                label: 'Prospectado até',
                placeholder: 'Prospectado até'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->numero(
                'quantidade',
                'Quantidade de registros',
                'Quantidade de registros',
                'Quantidade de registros'
            )
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha um status'),
                titulo: 'Status',
                label: 'Status',
                placeholder: 'Status'
            );
    });

return $Painel;
