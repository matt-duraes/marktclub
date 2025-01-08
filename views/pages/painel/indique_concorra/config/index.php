<?php

use App\Classes\ComercialEmpresa\ProspeccaoStatus;

$Painel = new PainelConfig\Index('indique-concorra');

return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->dataCriacao()
    ->status('prospeccao_status', 'Status', new ProspeccaoStatus());
