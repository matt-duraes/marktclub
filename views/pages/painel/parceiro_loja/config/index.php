<?php

use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Index('parceiro_loja', new Ordem());

return $Painel
    ->campo('titulo', 'Parceiro', 'grande')
    // ->campo('data_auditoria', 'Auditado em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
