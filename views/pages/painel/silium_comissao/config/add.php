<?php

use PainelConfig\Add;
use App\Classes\SiliumComissao\Status;

$Painel = new Add('silium_comissao', $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Informações da Compra', function () use ($Painel) {
        $Painel
            ->input(
                name: 'parceiro',
                label: 'Parceiro',
                placeholder: 'Insira o nome do parceiro/loja',
                contador: 100,
                obrigatorio: true
            )
            ->dinheiro(
                name: 'valor_compra',
                label: 'Valor da Compra',
                placeholder: 'Insira o valor da compra',
                obrigatorio: true
            )
            ->dinheiro(
                name: 'comissao_usuario',
                label: 'Comissão',
                placeholder: 'Insira o valor da comissão',
                obrigatorio: true
            )
            ->data(
                name: 'data_compra',
                label: 'Data da Compra',
                placeholder: 'Insira a data da compra',
                obrigatorio: true
            );
    });

    $Painel->fieldset('Informações da Pontuação', function () use ($Painel) {
        $Painel
            ->input(
                name: 'usuario',
                label: 'Usuário',
                placeholder: 'Insira a referência do usuário na compra',
                obrigatorio: true
            )/*
            ->numero(
                name: 'pontuacao',
                label: 'Pontuação Adquirida',
                placeholder: 'Insira a pontuação adquirida'
            )*/
            ->select(
                name: 'status',
                lista: (new Status())->select('Selecione um status'),
                label: 'Status',
                placeholder: 'Status',
                obrigatorio: true
            );
    });
});

return $Painel;
