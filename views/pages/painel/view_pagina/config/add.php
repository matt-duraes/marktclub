<?php

$Painel = new PainelConfig\Add(app: 'view_pagina', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título', placeholder: 'Digite um título')
            ->uri(name: 'url', label: 'URL', placeholder: 'Digite a url do clube');
    });
});

return $Painel;
