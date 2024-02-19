<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index(app: 'publicacao_arquivo');
return $Painel
    ->campo('titulo', 'Título', 'grande')
    ->status('status', 'Status', new Status());
