<?php

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Index('solicitacao_loja', new Ordem());

$Painel
    ->campo('clube.titulo', 'Clube', 'pequeno')
    ->campo('nome', 'Nome indicação', 'normal')
    ->campo('telefone', 'Telefone', 'pequeno', 'telefone')
    ->campo('email', 'E-mail', 'normal')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
