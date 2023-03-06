<?php

use App\Classes\StatusGeral\Status;

$Painel = new PainelConfig\Filtrar('publicacao_noticia');

$Painel
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_publicacao_de', titulo: 'Data de publicação de', label: 'Data de', placeholder: 'Data de publicação de')
            ->data(name: 'data_publicacao_ate', titulo: 'Data de publicação at;e', label: 'Data até', placeholder: 'Data de publicação até');
    })
    ->select(name: 'status', label: 'Status', lista: (new Status)->select('Escolha uma opção'));

$Painel->replace('status', (new Status())->select());

return $Painel;
