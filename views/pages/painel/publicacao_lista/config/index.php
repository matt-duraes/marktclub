<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoLista\Grupo;

$Painel = new PainelConfig\Index('publicacao_lista');
$Painel
    ->drag()
    ->campo('titulo', 'Título', 'grande')
    ->campo('grupo', 'Grupo', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('grupo', new Grupo());
return $Painel;
