<?php

use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Helper;
use App\Classes\SolicitacaoVoucher\Status;

$Painel = new PainelConfig\Index('solicitacao_voucher', new Ordem());
$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('empresa.nome_fantasia', 'Empresa', 'pequeno', permissao: Helper::PERMISSAO_EMPRESA)
    ->campo('tipo', 'Tipo', 'pequeno')
    ->dataCriacao()
    ->campo('data_vencimento', 'Válido até', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());

return $Painel;
