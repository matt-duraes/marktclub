<?php

use App\Classes\ComunicacaoPopup\Ordem;
use App\Classes\ComunicacaoPopup\Status;

$Painel = new PainelConfig\Index('comunicacao_popup', new Ordem());

$Painel
    ->drag()
    ->campo('empresa.nome', 'Empresa', 'normal')
    ->campo('titulo', 'Título', 'normal')
    ->campo('data_inicio', 'Data Início', 'pequeno', 'data')
    ->campo('data_final', 'Data Final', 'pequeno', 'data')
    ->status('status', 'Status', new Status());

return $Painel;
