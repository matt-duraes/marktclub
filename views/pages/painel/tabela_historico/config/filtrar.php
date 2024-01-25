<?php

use App\Classes\TabelaUsuario\Helper;
use App\Classes\TabelaUsuario\Status;
use App\Classes\TabelaUsuario\Tipo;

$Painel = new PainelConfig\Filtrar('tabela_historico');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        label: 'Status',
        placeholder: 'Status'
    )
    ->select(
        name: 'tipo',
        lista: (new Tipo())->select('Escolha um tipo'),
        label: 'Tipo',
        placeholder: 'Tipo'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_de', titulo: 'Data inicial', label: 'Data inicial', placeholder: 'Data inicial')
            ->data(name: 'data_ate', titulo: 'Data final', label: 'Data final', placeholder: 'Data final');
    });

return $Painel;
