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
        permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
    )
    ->input(name: 'nome', titulo: 'Nome do indicado', label: 'Nome', placeholder: 'Digite um nome')
    ->input(name: 'email', titulo: 'E-mail do indicado', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->select(
        name: 'status',
        lista: (new Status())->select('Selecione um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
