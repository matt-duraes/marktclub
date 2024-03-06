<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoDiretoria\Ordem;

$Painel = new PainelConfig\Index('publicacao_diretoria', new Ordem());
return $Painel
    ->drag()
    ->campo('nome', 'Nome', 'grande')
    ->dataCriacao()
    ->status('status', 'Status', new Status());
