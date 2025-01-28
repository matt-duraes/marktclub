<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar('usuario_grupo');

$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa',
        placeholder: 'Empresa',
        permissao: 'usuario_grupo_empresa'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha um status'),
        titulo: 'Status',
        label: 'Status',
        placeholder: 'Status'
    );

return $Painel;
