<?php

use App\Classes\SolicitacaoVoucher\Status;

$Status = new Status();

$Painel = new PainelConfig\Filtrar('solicitacao_voucher');

$Painel
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Criado em', label: 'Criado em')
            ->data(name: 'data_criacao_ate', titulo: 'Criado até', label: 'Criado até');
    })
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $Status->select('Escolha uma opção'));

$Painel->replace('status', $Status->select());

return $Painel;
