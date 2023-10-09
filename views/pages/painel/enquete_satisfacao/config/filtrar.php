<?php

use App\Classes\EnqueteSatisfacao\Status;

$Painel = new PainelConfig\Filtrar('enquete_satisfacao');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
