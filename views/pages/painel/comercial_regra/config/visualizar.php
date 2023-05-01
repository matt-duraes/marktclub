<?php

$Painel = new PainelConfig\Visualizar('comercial_regra');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(callback: function () use ($Painel) {
        $Painel
        ->titulo('titulo')
        ->texto('texto');
    });
});

return $Painel;
