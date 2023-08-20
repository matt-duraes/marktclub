<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar('comunicacao-publicidade');

$Painel
    ->input(name: 'titulo', titulo: 'Título', label: 'Título', placeholder: 'Digite um título')
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_inicio', titulo: 'Data inicial', label: 'Data de incício')
            ->data(name: 'data_final', titulo: 'Data final', label: 'Data final');
    })
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: (new Status())->select('Escolha um status'))
    ->select(name: 'publicado', label: 'Publicado', lista: [
        ''    => 'Escolha uma opção',
        'sim' => 'Publicado',
        'nao' => 'Não publicado'
    ]);

$Painel->replace('status', (new Status())->select());

return $Painel;
