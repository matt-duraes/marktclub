<?php

use App\Classes\ComunicacaoContato\Status;

$Painel = new PainelConfig\Filtrar('comunicacao_contato');

$Painel
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite um nome')
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
                titulo: 'Data contato de',
                label: 'Data contato de',
                placeholder: 'Data contato de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Data contato até',
                label: 'Data contato até',
                placeholder: 'Data contato até'
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
