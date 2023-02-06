<?php

use Helpers\ApiHelper;

$empresa = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha um cliente'])
    ->get('/admin-empresa/select')
    ->array()['dado'] ?? [];

$parceiro = [
    '' => 'Escolha um parceiro',
    '890713a200a9e45aa85e2ae67aa41e74' => 'Sala Vip Anafe'
];

$Painel = new PainelConfig\Add('parceiro_relatorio');

$Painel->coluna(callback: function () use ($Painel, $parceiro, $empresa) {
    $Painel->fieldset('Dados', function () use ($Painel, $parceiro, $empresa) {
        $Painel
            ->select(name: 'parceiro', label: 'Parceiro', lista: $parceiro)
            ->select(name: 'empresa', label: 'Empresa', lista: $empresa);
    });
    $Painel->fieldset('Dados', function () use ($Painel) {
        $Painel
            ->numero(name: 'numero_transacao', label: 'Número de transações')
            ->input(name: 'valor_venda', label: 'Valor de vendas', mascara: 'dinheiro')
            ->data(name: 'data_relatorio', label: 'Data do relatório');
    });
});

return $Painel;
