<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('construtor-clube');
$Painel
    ->campo('titulo', 'Clube', 'grande')
    ->campo('empresa.titulo', 'Empresa', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('status', new Status());

return $Painel;
