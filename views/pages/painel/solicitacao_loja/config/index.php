<?php

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Index('solicitacao_loja', new Ordem());

$Painel
    ->campo('nome', 'Nome indicação', 'normal')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->campo('email', 'E-mail', 'normal')
    ->campo('origem', 'Origem', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('origem', (new Origem())->select());

return $Painel;
