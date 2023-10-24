<?php

use App\Classes\ParceiroCupom\Ordem;
use App\Classes\ParceiroCupom\Status;

$Painel = new PainelConfig\Index('parceiro_cupom', new Ordem());

$Painel
    ->campo('titulo', 'Título', 'normal')
    ->campo('cupom_link', 'Cupom/Link', 'normal')
    ->campo('data_validade', 'Data de validade', 'normal', formatar: 'data')
    ->status('status', 'Status', new Status());

$Painel->js('painel_parceiro_cupom_index');
$Painel->css('painel_parceiro_cupom_index');

return $Painel;
