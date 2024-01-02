<?php

use App\Classes\ComercialPopup\Ordem;
use App\Classes\ComercialPopup\Status;

$Painel = new PainelConfig\Index('comercial_popup', new Ordem());

$Painel
    ->drag()
    ->campo('titulo_painel', 'Título', 'normal')
    ->campo('data_inicio', 'Data Início', 'pequeno', 'data')
    ->campo('data_final', 'Data Final', 'pequeno', 'data')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', [
    'sim' => 'Sim',
    'nao' => 'Não'
]);

return $Painel;
