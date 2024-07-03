<?php

use PainelConfig\Add;

$Painel = new Add('silium_config', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Regras Resgate', function () use ($Painel) {
        $Painel
            ->numero(
                name: 'pontuacao_dinheiro',
                label: 'Pontuação Miníma Resgate (Dinheiro)',
                placeholder: 'Insira a pontuação miníma para resgate em dinheiro',
                obrigatorio: true
            )
            ->numero(
                name: 'pontuacao_mensalidade',
                label: 'Pontuação Miníma Resgate (Desconto Mensalidade)',
                placeholder: 'Insira a pontuação miníma para desconto na mensalidade',
                obrigatorio: true
            )
            ->numero(
                name: 'validade_pontuacao',
                label: 'Prazo de Validade',
                placeholder: 'Insira o prazo de validade da pontuação (Em meses)'
            );
    });
});

return $Painel;
