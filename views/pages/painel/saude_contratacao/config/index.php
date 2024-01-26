<?php

use App\Classes\Saude\Ordem;
use App\Classes\Saude\Status;

$Painel = new PainelConfig\Index('saude_contratacao', new Ordem());

return $Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('cpf', 'CPF', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
