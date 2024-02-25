<?php

use PainelConfig\Index;
use App\Classes\Geral\Status;

$Painel = new Index('votacao');
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Começa em', 'pequeno', formatar: Index::FORMATAR_DATAHORA)
    ->campo('data_final', 'Termina em', 'pequeno', formatar: Index::FORMATAR_DATAHORA)
    ->status('status', 'Status', new Status());
