<?php

use App\Classes\SolicitacaoLoja\Status;

$Painel = new PainelConfig\Filtrar('solicitacao_loja');

$Painel
    ->select(
        name: 'status',
        lista: (new Status())->select('Escolha uma opção'),
        label: 'Status'
    );

$Painel->replace('status', (new Status())->select());

return $Painel;
