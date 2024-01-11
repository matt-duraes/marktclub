<?php

use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;

$Painel = new PainelConfig\Index('texto_clube');
$Painel
    ->drag()
    ->campo('titulo_painel', 'Título', 'grande')
    ->dataCriacao()
    ->campo('tipo', 'Tipo', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('tipo', new Tipo());

return $Painel;
