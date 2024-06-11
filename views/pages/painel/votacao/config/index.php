<?php

use PainelConfig\Index;
use App\Classes\Votacao\Dado\Status;

$Painel = new Index(isset($enqueteApp) ? $enqueteApp : 'votacao');
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Começa em', 'pequeno', formatar: Index::FORMATAR_DATAHORA)
    ->campo('data_final', 'Termina em', 'pequeno', formatar: Index::FORMATAR_DATAHORA)
    ->status('status', 'Status', new Status());
