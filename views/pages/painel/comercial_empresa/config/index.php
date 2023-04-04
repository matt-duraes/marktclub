<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Index('comercial_empresa', new Ordem());

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('cnpj', 'CNPJ', 'pequeno', formatar: 'cnpj')
    ->campo('data_criacao', 'Criado em', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
