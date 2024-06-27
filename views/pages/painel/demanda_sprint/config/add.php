<?php

$Painel = new PainelConfig\Add(app: 'demanda_spring', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados da sprint', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título')
            ->data(name: 'data_inicio', label: 'Início da sprint', placeholder: 'Início da sprint')
            ->data(name: 'data_final', label: 'Final da sprint', placeholder: 'Final da sprint');
    });
});

return $Painel;
