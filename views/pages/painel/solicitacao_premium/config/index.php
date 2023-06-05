<?php

use App\Classes\SolicitacaoPremium\Status;

$Painel = new PainelConfig\Index('solicitacao_premium');
$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('limite', 'Limite', 'pequeno')
    ->campo('disponivel', 'Disponíveis', 'pequeno')
    ->campo('ativo', 'Pendentes', 'pequeno')
    ->campo('validado', 'Utilizado', 'pequeno')
    ->campo('cancelado', 'Cancelados', 'pequeno')
    ->campo('total', 'Todos', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
