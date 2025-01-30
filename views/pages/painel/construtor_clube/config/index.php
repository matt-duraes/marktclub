<?php

use App\Classes\ConstrutorClube\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('construtor-clube', new Ordem());

$Painel
    ->campo('empresa.titulo', 'Empresa', 'normal')
    ->campo('titulo', 'Clube', 'grande')
    ->campo('app_versao', 'Versão do App', 'normal')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

$Painel->replace('status', new Status());

return $Painel;
