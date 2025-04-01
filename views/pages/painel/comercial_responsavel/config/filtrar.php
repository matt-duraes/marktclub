<?php

use App\Classes\ComercialEmpresa\Status;
use App\Classes\UsuarioEquipe\Tipo;
use PainelConfig\Filtrar;

$Painel = new Filtrar('comercial_responsavel');

$Status = new Status();
$Painel
    ->input(
        name: 'titulo',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Título'
    )
    ->select(
        name: 'equipe',
        lista: 'usuario',
        titulo: 'Equipe',
        label: 'Equipe',
        placeholder: 'Equipe',
        tipoEquipe: Tipo::COMERCIAL
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                'data_inicio',
                'Data de Início',
                'Data de Início',
                'Data de Início'
            )
            ->data(
                'data_final',
                'Data Final',
                'Data Final',
                'Data Final'
            );
    })
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

return $Painel;
