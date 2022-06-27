<?php

use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;

$Painel = new PainelConfig\Index('solicitacao_voucher', new Ordem());
$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('data_criacao', 'Criado em', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
