<?php

use App\Classes\AdminEmpresa\Status;

$Painel = new PainelConfig\Index('comercial_empresa');

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('cnpj', 'CNPJ', 'pequeno', formatar: 'cnpj')
    ->campo('data_contrato', 'Contrato desde', 'pequeno')
    ->status('status', 'Status', new Status);
