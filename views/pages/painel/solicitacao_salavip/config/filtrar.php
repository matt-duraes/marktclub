<?php

use App\Classes\SolicitacaoSalavip\Empresa;

$Empresa = new Empresa();

$Painel = new PainelConfig\Filtrar('solicitacao_voucher');

$Painel
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_de', titulo: 'Validado em', label: 'Validado em')
            ->data(name: 'data_ate', titulo: 'Validado até', label: 'Validado até');
    })
    ->select(name: 'empresa', titulo: 'Empresa', label: 'Empresa', lista: $Empresa->select('Escolha uma opção'));

$Painel->replace('empresa', $Empresa->select());

return $Painel;
