<?php

use App\Classes\PontoCvs\Ordem;
use App\Classes\PontoCvs\Status;

$Painel = new PainelConfig\Index('ponto_cvs', new Ordem());

return $Painel
    ->campo('usuario', 'Usuario', 'normal')
    ->campo('ponto', 'Ponto Solicitado', 'pequeno')
    ->campo('data_solicitacao', 'Solicitado em', 'pequeno')
    ->status('status', 'Status', new Status());
