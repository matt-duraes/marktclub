<?php

use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;

$Painel = new PainelConfig\Index('carteirinha', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', 'normal')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
