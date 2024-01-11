<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('site_config');

$Painel
    ->campo('titulo_painel', 'Titulo', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
