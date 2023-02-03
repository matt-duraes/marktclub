<?php

$Painel = new PainelConfig\Index('parceiro_relatorio');

return $Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('empresa', 'Empresa', 'normal')
    ->campo('data_relatorio', 'Relatório de', 'pequeno');
