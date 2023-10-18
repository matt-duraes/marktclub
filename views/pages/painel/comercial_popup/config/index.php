<?php

use App\Classes\ComercialPopup\Ordem;
use App\Classes\ComercialPopup\Status;

$Painel = new PainelConfig\Index('comercial_popup', new Ordem());

$Painel
    ->drag()
    ->campo('empresa.nome', 'Empresa', 'normal', permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_EMPRESA)
    ->campo('titulo', 'Título', 'normal')
    ->campo('data_inicio', 'Data Início', 'pequeno', 'data')
    ->campo('data_final', 'Data Final', 'pequeno', 'data')
    ->status('status', 'Status', new Status());

return $Painel;
