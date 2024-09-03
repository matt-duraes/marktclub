<?php

use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;

$Painel = new PainelConfig\Filtrar('parceiro_campanha');

$Painel
    ->input(name: 'titulo', titulo: 'Título', label: 'Título', placeholder: 'Digite um título')
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', titulo: 'Data inicial', label: 'Data de início')
            ->data(name: 'data_final', titulo: 'Data final', label: 'Data final');
    })
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: (new Status())->select('Escolha um status'))
    ->select(name: 'publicado', label: 'Publicado', lista: [
        ''             => 'Escolha uma opção',
        Publicado::SIM => 'Publicado',
        Publicado::NAO => 'Não publicado'
    ]);

$Painel->replace('status', (new Status())->select());

return $Painel;
