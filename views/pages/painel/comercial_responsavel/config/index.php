<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;
use PainelConfig\Index;

$Painel = new Index('comercial_responsavel', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', Index::TIPO_GRANDE)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
