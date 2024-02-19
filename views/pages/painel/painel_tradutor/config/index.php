<?php

use App\Classes\Geral\Status;
use App\Classes\PainelTradutor\Ordem;

$Painel = new PainelConfig\Index('painel_tradutor', new Ordem());

$Painel
    ->campo('termo', 'Termo', 'normal')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
