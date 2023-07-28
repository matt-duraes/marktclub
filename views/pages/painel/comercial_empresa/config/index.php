<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Index('comercial_empresa', new Ordem());

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('cnpj', 'CNPJ', 'pequeno', formatar: 'cnpj')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
