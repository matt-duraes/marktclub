<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Index('comercial_subempresa', new Ordem());

$Painel
    ->campo('empresa_matriz.nome_fantasia', 'Empresa', 'grande')
    ->campo('nome_fantasia', 'Nome', 'grande')
    ->campo('cnpj', 'CNPJ', 'normal', 'cnpj')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
