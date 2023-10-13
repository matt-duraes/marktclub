<?php

use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\TipoUsuario;

$Painel = new PainelConfig\Index('solicitacao_voucher', new Ordem());

$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('tipo_usuario', 'Usuário', 'pequeno')
    ->campo('data_vencimento', 'Válido até', 'pequeno', 'datahora')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo', (new Tipo())->select());
$Painel->replace('tipo_usuario', (new TipoUsuario())->select());

return $Painel;
