<?php

use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Index('parceiro_loja', new Ordem());

return $Painel
    ->campo('titulo_interno', 'Parceiro', 'grande')
    ->campo('tipo_loja', 'Tipo', 'pequeno')
    ->campo('data_auditoria', 'Auditado em', 'pequeno')
    ->status('status', 'Status', new Status());
