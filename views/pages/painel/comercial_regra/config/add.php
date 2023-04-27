<?php

$Painel = new PainelConfig\Add(app: 'comercial-regra', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título da regra')
            ->editor(name: 'texto', label: 'Texto');
    });
});

return $Painel;
