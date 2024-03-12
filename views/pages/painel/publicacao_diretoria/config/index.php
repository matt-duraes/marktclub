<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('publicacao_diretoria');
return $Painel
    ->drag()
    ->campo('nome', 'Nome', 'grande')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
