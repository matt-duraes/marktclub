<?php

use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;

$Painel = new PainelConfig\Index('parceiro_loja', new Ordem());

$Painel
    ->campo('titulo_interno', 'Parceiro', 'grande')
    ->campo('tipo_loja', 'Tipo', 'pequeno')
    ->campo('data_auditoria', 'Auditado em', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('tipo_loja', (new TipoLoja())->select());
$Painel->css('painel_parceiro_loja_index');
$Painel->js('painel_parceiro_loja_index');
return $Painel;
