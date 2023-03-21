<?php

use App\Classes\SolicitacaoVoucher\Helper;
use App\Classes\SolicitacaoVoucher\Status;

$Status = new Status();

$Painel = new PainelConfig\Filtrar('solicitacao_voucher');

$Painel
    ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Criado em', label: 'Criado em')
            ->data(name: 'data_criacao_ate', titulo: 'Criado até', label: 'Criado até');
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_validacao_de', titulo: 'Validado em', label: 'Validado em')
            ->data(name: 'data_validacao_ate', titulo: 'Validado até', label: 'Validado até');
    })
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $Status->select('Escolha uma opção'));

$Painel->replace('status', $Status->select());

return $Painel;
