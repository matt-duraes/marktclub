<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Ordem;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;

$Painel = new PainelConfig\Index('solicitacao_credito', new Ordem());

$Painel
    ->campo('usuario.nome', 'Usuário', 'normal')
    ->campo('operadora', 'Operadora', 'pequeno')
    ->campo('tipo', 'Tipo', 'pequeno')
    ->campo('valor_total', 'Valor Total', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('operadora', (new Operadora())->select());
$Painel->replace('tipo', (new Tipo())->select());

return $Painel;
