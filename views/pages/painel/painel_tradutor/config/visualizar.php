<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Visualizar('painel_tradutor');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Traduções', callback: function () use ($Painel) {
        $Painel
            ->linha('termo', 'Texto')
            ->linha('traducao_en', 'Tradução em Inglês')
            ->linha('traducao_es', 'Tradução em Espanhol');
    });

    $Painel->bloco('Outras Informações', callback: function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Data de Criação')
            ->dataHora('data_atualizacao', 'Data da Última Atualização')
            ->linha('status', 'Status');
    });
});

$Painel->replace('status', (new Status())->select());

return $Painel;
