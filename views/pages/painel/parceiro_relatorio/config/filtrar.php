<?php

$Painel = new PainelConfig\Filtrar('parceiro_relatorio');

$Painel
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_relatorio_de', titulo: 'Data de', label: 'Data de', placeholder: 'Digite uma data')
            ->data(name: 'data_relatorio_ate', titulo: 'Data até', label: 'Data até', placeholder: 'Digite uma data');
    });

return $Painel;
