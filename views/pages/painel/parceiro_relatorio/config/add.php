<?php

use Helpers\ApiHelper;

$empresa = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um cliente'])
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$parceiro = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um parceiro'])
    ->get('/parceiro-loja/select')
    ->array()['dado'] ?? [];

$Painel = new PainelConfig\Add('parceiro_relatorio');

$Painel->coluna(callback: function () use ($Painel, $parceiro, $empresa) {
    $Painel->fieldset('Dados', function () use ($Painel, $parceiro, $empresa) {
        $Painel
            ->select(name: 'parceiro->id', lista: $parceiro, label: 'Parceiro')
            ->select(name: 'empresa->id', lista: $empresa, label: 'Empresa');
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->numero(name: 'numero_transacao', label: 'Número de transações')
            ->dinheiro(name: 'valor_venda', label: 'Valor de vendas')
            ->data(name: 'data_relatorio', label: 'Data do relatório');
    });
});

return $Painel;
