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
                obrigatorio: true
            )
            ->dinheiro(
                name: 'valor_compra',
                label: 'Valor da Compra (Reais)',
                placeholder: 'Insira o valor da compra (Reais)',
                obrigatorio: true
            )
            ->dinheiro(
                name: 'comissao_usuario',
                label: 'Comissão (Reais)',
                placeholder: 'Insira o valor da comissão (Reais)',
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
            )
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
