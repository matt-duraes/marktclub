<?php

use Helpers\ApiHelper;

$empresa = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um cliente'])
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$parceiro = require 'parceiro.php';

$Painel = new PainelConfig\Add('parceiro_relatorio');

$Painel->coluna(callback: function () use ($Painel, $parceiro, $empresa) {
    $Painel->fieldset('Dados', function () use ($Painel, $parceiro, $empresa) {
        $Painel
            ->select(name: 'parceiro->id', label: 'Parceiro', lista: $parceiro)
            ->select(name: 'empresa->id', label: 'Empresa', lista: $empresa);
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->numero(name: 'numero_transacao', label: 'Número de transações')
            ->dinheiro(name: 'valor_venda', label: 'Valor de vendas')
            ->data(name: 'data_relatorio', label: 'Data do relatório');
    });
});

return $Painel;
