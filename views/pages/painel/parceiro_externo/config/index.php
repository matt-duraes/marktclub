<?php

use App\Classes\ParceiroLoja\Status;
use App\Classes\Parceiro\Externo\Ordem;

$Painel = new PainelConfig\Index('parceiro_externo', new Ordem());

$Painel
    ->campo('titulo_interno', 'Parceiro', 'grande')
    ->campo('data_criacao', 'Data', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
