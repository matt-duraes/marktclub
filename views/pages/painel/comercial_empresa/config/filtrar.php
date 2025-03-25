<?php

use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Filtrar('comercial-empresa');

$Status = new Status();
$Painel
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Digite o título, nome fantasia ou razão social'
    )
    ->cnpj(
        name: 'cnpj',
        titulo: 'CNPJ',
        label: 'CNPJ',
        placeholder: 'Digite o CNPJ'
    )
    ->select(
        name: 'usuario',
        lista: 'usuario',
        titulo: 'Gestor do contrato',
        label: 'Gestor do contrato'
    )
    ->select(
        name: 'status',
        lista: $Status->select('Escolha uma opção'),
        titulo: 'Status',
        label: 'Status'
    );

$Painel->replace('status', $Status->select());

return $Painel;
