<?php

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Index('solicitacao_loja', new Ordem());

$Painel
    ->campo('nome', 'Nome', 'grande')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->campo('email', 'E-mail', 'normal')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
