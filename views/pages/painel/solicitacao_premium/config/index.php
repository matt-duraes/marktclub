<?php

use App\Classes\StatusGeral\Status;

$Painel = new PainelConfig\Index('solicitacao_premium');
$Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('limite', 'Limite', 'pequeno')
    ->campo('gerado', 'Gerado', 'pequeno')
    ->campo('validado', 'Validado', 'pequeno')
    ->status('status', 'Status', new Status());

return $Painel;
