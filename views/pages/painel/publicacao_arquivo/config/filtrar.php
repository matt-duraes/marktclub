<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoArquivo\Tipo;
use Modules\Botao;

$Painel = new PainelConfig\Filtrar('publicacao_arquivo');

$Tipo = new Tipo();
$Botao = new Botao();
$Status = new Status();
$Painel
    ->select(
        name: 'publicado',
        lista: $Botao->select('Escolha uma opção'),
        titulo: 'Publicado',
        label: 'Publicado',
        placeholder: 'Publicado'
    )
    ->bloco(function () use ($Painel, $Tipo, $Botao) {
        $Painel
            ->select(
                name: 'tipo',
                lista: $Tipo->select('Escolha uma opção'),
                titulo: 'Tipo de arquivo',
                label: 'Tipo de arquivo',
                placeholder: 'Tipo de arquivo'
            )
            ->select(
                name: 'site',
                lista: $Botao->select('Escolha uma opção'),
                titulo: 'Disponível no Site?',
                label: 'Disponível no Site?',
                placeholder: 'Disponível no Site?'
            )
            ->select(
                name: 'restrita',
                lista: $Botao->select('Escolha uma opção'),
                titulo: 'Disponível na Área Restrita?',
                label: 'Disponível na Área Restrita?',
                placeholder: 'Disponível na Área Restrita?'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_inicio',
                titulo: 'Publicado de',
                label: 'Publicado de',
                placeholder: 'Publicado de'
            )
            ->data(
                name: 'data_final',
                titulo: 'Publicado até',
                label: 'Publicado até',
                placeholder: 'Publicado até'
            );
    })
    ->bloco(function () use ($Painel, $Status) {
        $Painel
            ->numero(
                name: 'quantidade',
                titulo: 'Quantidade',
                label: 'Quantidade',
                placeholder: 'Quantidade de Registros'
            )
            ->select(
                name: 'status',
                lista: $Status->select('Escolha um status'),
                titulo: 'Status',
                label: 'Status',
                placeholder: 'Status'
            );
    });

return $Painel;
