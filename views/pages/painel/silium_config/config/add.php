<?php

use PainelConfig\Add;

$Painel = new Add('silium_config', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Regras Resgate', function () use ($Painel) {
        $Painel
            ->select(
                name: 'empresa',
                lista: 'empresa',
                label: 'Selecione a Empresa',
                obrigatorio: true
            )
            ->select(
                name: 'desconto',
                lista: ['1' => 'Sim', '2' => 'Não'],
                label: 'Tem resgate via Desconto na Mensalidade?',
                obrigatorio: true
            )
            ->numero(
                name: 'pontuacao_dinheiro',
                label: 'Pontuação Miníma Resgate (Dinheiro)',
                placeholder: 'Insira a pontuação miníma para resgate em dinheiro',
                obrigatorio: true
            )
            ->numero(
                name: 'pontuacao_mensalidade',
                label: 'Pontuação Miníma Resgate (Desconto Mensalidade)',
                placeholder: 'Insira a pontuação miníma para desconto na mensalidade'
            )
            ->numero(
                name: 'validade_pontuacao',
                label: 'Prazo de Validade',
                placeholder: 'Insira o prazo de validade da pontuação (Em meses)'
            );
    });
});

return $Painel;
