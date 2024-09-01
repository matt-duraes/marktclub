<?php

use App\Classes\Geral\Status;
use App\Classes\Parceiro\Turismo\Ordem;

$Painel = new PainelConfig\Index('parceiro_turismo', new Ordem());

$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('data_inicio', 'Valido de', 'pequeno', formatar: 'data')
    ->campo('data_final', 'até', 'pequeno', formatar: 'data')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', ['sim' => 'Sim', 'nao' => 'Não']);
$Painel->replace('status', (new Status())->select());

return $Painel;
