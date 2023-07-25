<?php

use App\Classes\StatusGeral\Status;
use App\Classes\ParceiroEasylive\Tipo;

$Painel = new PainelConfig\Index('parceiro_easylive');

$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('data_validade', 'Valido até', 'pequeno', formatar: 'data')
    ->status('status', 'Status', new Status());
$Painel->replace('tipo', new Tipo());
return $Painel;
