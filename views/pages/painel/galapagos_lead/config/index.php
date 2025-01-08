<?php

use PainelConfig\Index;
use App\Classes\Galapagos\Lead\Ordem;
use App\Classes\Galapagos\Lead\Status;

$Painel = new Index('galapagos_lead', new Ordem());

$Painel
    ->campo('nome', 'Nome', Index::TIPO_NORMAL)
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
