<?php

use App\Classes\ParceiroLoja\Status;

$Painel = new PainelConfig\Index('parceiro_equipe');

$Painel
    ->campo('titulo_interno', 'Parceiro', 'grande')
    ->campo('data_criacao', 'Data', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
