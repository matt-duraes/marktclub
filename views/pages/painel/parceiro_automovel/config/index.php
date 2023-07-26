<?php

use App\Classes\StatusGeral\Status;

$Painel = new PainelConfig\Index('parceiro_automovel');

$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('parceiro', 'Parceiro', 'normal')
    ->status('status', 'Status', new Status());

return $Painel;
