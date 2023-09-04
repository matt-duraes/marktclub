<?php

use App\Classes\ParceiroCupom\Ordem;
use App\Classes\ParceiroCupom\Auditado;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('parceiro_cupom', new Ordem());

$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('cupom', 'Cupom', 'grande')
    ->campo('validade', 'Validade', 'grande', 'datahora')
    ->campo('auditado', 'Auditado', 'grande')
    ->status('status', 'Status', new Status());

$Painel->replace('auditado', new Auditado());

return $Painel;
