<?php

use App\Classes\ComercialSubempresa\Status;

$Painel = new PainelConfig\Filtrar('comercial_subempresa');

$Painel
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Digite o título'
    )
    ->select(
        name: 'empresa',
        lista: 'empresa',
        titulo: 'Empresa',
        label: 'Empresa'
    )
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha uma opção'),
        titulo: 'Status',
        label: 'Status'
    );

return $Painel;
