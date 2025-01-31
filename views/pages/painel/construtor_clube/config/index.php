<?php

use App\Classes\ConstrutorClube\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('construtor-clube', new Ordem());

$Painel
    ->campo('empresa.titulo', 'Empresa', 'normal')
    ->campo('titulo', 'Clube', 'grande')
    ->campo('app_versao_android', 'Versão do App Android', 'pequeno')
    ->campo('app_versao_ios', 'Versão do App IOS', 'pequeno')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

$Painel->replace('status', new Status());

return $Painel;
