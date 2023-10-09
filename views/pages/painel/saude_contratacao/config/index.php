<?php

use App\Classes\PublicacaoPagina\Ordem;
use App\Classes\Saude\Status;

$Painel = new PainelConfig\Index('saude_contratacao', new Ordem());
return $Painel
    ->campo('nome', 'nome', 'grande')
    ->campo('cpf', 'CPF', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
