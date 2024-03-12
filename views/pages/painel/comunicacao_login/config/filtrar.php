<?php

use App\Classes\Geral\Status;
use Modules\Botao;

$Painel = new PainelConfig\Filtrar('comunicacao_login');

$Painel
    ->input(
        name: 'titulo_banner',
        titulo: 'Título',
        label: 'Título',
        placeholder: 'Título'
    )
    ->select(
        name: 'publicado',
        lista: (new Botao())->select('Escolha uma opção'),
        titulo: 'Publicado',
        label: 'Publicado',
        placeholder: 'Publicado'
    )
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Data Início',
                label: 'Data de início',
                placeholder: 'Data de início'
            )
            ->data(
                name: 'data_final',
                titulo: 'Data Final',
                label: 'Data final',
                placeholder: 'Data final'
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
