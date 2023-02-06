<?php

use App\Classes\ParceiroRelatorio\Ordem;

$Painel = new PainelConfig\Index('parceiro_relatorio', new Ordem());

return $Painel
    ->campo('parceiro', 'Parceiro', 'grande')
    ->campo('empresa', 'Empresa', 'grande')
    ->campo('data_relatorio', 'Relatório de', 'pequeno', formatar: 'data');
