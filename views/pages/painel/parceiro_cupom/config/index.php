<?php

use App\Classes\ParceiroCupom\Ordem;
use App\Classes\ParceiroCupom\Auditado;

$Painel = new PainelConfig\Index('parceiro_cupom', new Ordem());

return $Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('descricao', 'Descrição', 'grande')
    ->campo('cupom', 'Cupom', 'grande')
    ->campo('validade', 'Validade', 'grande')
    ->status('auditado', 'auditado', new Auditado());
