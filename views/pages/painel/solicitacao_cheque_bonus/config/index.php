<?php

use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Classes\Solicitacao\Status;

$Painel = new PainelConfig\Index('solicitacao_cheque_bonus', new Ordem());

return $Painel
    ->campo('nome', 'Nome', 'normal')
    ->campo('tipo_usuario', 'Tipo de usuário', 'normal')
    ->status('status', 'Status', new Status());
