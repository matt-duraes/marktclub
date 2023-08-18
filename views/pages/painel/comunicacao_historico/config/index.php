<?php

use App\Classes\Geral\Status;
use App\Classes\ComunicacaoHistorico\Ordem;

$Painel = new PainelConfig\Index('comunicacao-historico', new Ordem());
$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('parceiro.titulo', 'Parceiro', 'normal')
    ->campo('data_inicio', 'Data de início', 'pequeno')
    ->campo('data_final', 'Data final', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
