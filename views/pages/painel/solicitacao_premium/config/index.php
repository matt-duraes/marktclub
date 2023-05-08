<?php

use App\Classes\SolicitacaoPremium\Status;

$Painel = new PainelConfig\Index('solicitacao_premium');
$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('limite', 'Limite', 'pequeno')
    ->campo('total', 'Todos', 'pequeno')
    ->campo('ativo', 'Não utilizado', 'pequeno')
    ->campo('validado', 'Utilizado', 'pequeno')
    ->campo('cancelado', 'Cancelados', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
