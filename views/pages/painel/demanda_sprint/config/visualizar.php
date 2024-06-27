<?php

$Painel = new PainelConfig\Visualizar('demanda_spring');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados da sprint', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->data('data_inicio', 'Início da sprint')
            ->data('data_final', 'Final da sprint');
    });
});

return $Painel;
