<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Index('comercial_perdido', new Ordem());

return $Painel
    ->campo('titulo', 'Título', 'normal')
    ->campo('cnpj', 'CNPJ', 'pequeno', formatar: 'cnpj')
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());
