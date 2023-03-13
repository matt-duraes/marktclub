<?php

$Painel = new PainelConfig\Download('solicitacao_salavip');

return $Painel
    ->bloco('Campos', function () use ($Painel) {
        $Painel
            ->campo('empresa', 'Empresa')
            ->campo('codigo', 'Código')
            ->campo('data', 'Data de validação');
    });

return $Painel;
