<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Ordem;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;

$Painel = new PainelConfig\Index('solicitacao_credito', new Ordem());

$Painel
    ->campo('operadora', 'Operadora', 'normal')
    ->campo('tipo', 'Tipo', 'normal')
    ->campo('valor_total', 'Valor Total', 'normal')
    ->status('status', 'Status', new Status());

$Painel->replace('operadora', (new Operadora())->select());
$Painel->replace('tipo', (new Tipo())->select());

return $Painel;
