<?php

$Painel = new PainelConfig\Add('painel_tradutor', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel
        ->input(
            name: 'termo',
            label: 'Digite um texto',
            placeholder: 'Digite um texto'
        )
        ->input(
            name: 'traducao[]',
            label: 'Tradução Inglês',
            placeholder: 'Digite a tradução em inglês',
            id: 'traducao_en'
        )
        ->input(
            name: 'traducao[]',
            label: 'Tradução Espanhol',
            placeholder: 'Digite a tradução em espanhol',
            id: 'traducao_es'
        );
});

$Painel->js('painel_painel_tradutor_add');

return $Painel;
