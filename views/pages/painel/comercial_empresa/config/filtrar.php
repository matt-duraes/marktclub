<?php

use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Filtrar('comercial-empresa');

$Painel
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Digite o título, nome fantasia ou razão social'
    )
    ->select(
        name: 'equipe',
        titulo: 'Gestor do contrato',
        label: 'Gestor do contrato',
        lista: 'usuario'
    )
    ->select(
        name: 'status',
        titulo: 'Status',
        label: 'Status',
        lista: (new Status())->select('Escolha uma opção')
    );

    $Painel->replace('status', (new Status())->select());

    return $Painel;
