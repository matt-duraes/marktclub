<?php

use App\Classes\ComercialEmpresa\Status;
use App\Classes\UsuarioEquipe\Tipo;

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
        label: 'Gestor do contrato',
        tipoEquipe: Tipo::COMERCIAL
    )
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->numero(
                'quantidade',
                'Quantidade de registros',
                'Quantidade de registros',
                'Quantidade de registros'
            )
            ->select(
                'status',
                $Status->select('Escolha um status'),
                'Status',
                'Status',
                'Status'
            );
    });

$Painel->replace('status', $Status->select());

return $Painel;
