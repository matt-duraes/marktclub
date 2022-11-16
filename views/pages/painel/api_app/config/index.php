<?php

use App\Classes\ApiApp\Ordem;
use App\Classes\ApiApp\Status;

$Painel = new PainelConfig\Index('api_app', new Ordem);
$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('dono', 'Dono', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
