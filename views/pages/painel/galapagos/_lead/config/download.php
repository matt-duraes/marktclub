<?php

$Painel = new PainelConfig\Download(app: 'galapagos__lead');

$Painel
    ->bloco('Título', function () use ($Painel) {
        $Painel
            ->campo('indice', 'Nome');
    })

return $Painel;
