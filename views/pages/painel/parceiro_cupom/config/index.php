<?php

use App\Classes\ParceiroCupom\Ordem;
use App\Classes\ParceiroCupom\Status;

$Painel = new PainelConfig\Index('parceiro_cupom', new Ordem());

$Painel
    ->campo('titulo', 'Título', 'normal')
    ->campo('cupom_link', 'Cupom/Link', 'normal')
    ->campo('data_validade', 'Data de validade', 'normal', formatar: 'data')
    ->botaoStatus('status', 'Status', new Status());

return $Painel;
