<?php

use App\Classes\UsuarioIndicacao\Status;

$Painel = new PainelConfig\Filtrar('usuario_indicacao');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\UsuarioIndicacao\Helper::PERMISSAO_EMPRESA
    )
    ->input(name: 'nome', titulo: 'Nome do indicado', label: 'Nome', placeholder: 'Digite um nome')
    ->input(name: 'email', titulo: 'E-mail do indicado', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', titulo: 'Data Inicío', label: 'Data Inicío', placeholder: 'Data de Inicío')
            ->data(name: 'data_final', titulo: 'Data Final', label: 'Data Final', placeholder: 'Data Final');
    })
    ->select(
        name: 'status',
        lista: (new Status())->select('Selecione um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
