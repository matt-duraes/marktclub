<?php

use App\Classes\ComercialSubempresa\Ordem;
use App\Classes\ComercialSubempresa\Status;

$Painel = new PainelConfig\Index('comercial_subempresa', new Ordem());

$Painel
    ->campo('empresa_matriz.nome', 'Empresa', 'grande')
    ->campo('nome', 'Nome', 'grande')
    ->campo('documento_cnpj', 'CNPJ', 'normal', 'cnpj')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
