<?php

$Painel = new PainelConfig\Download('solicitacao_salavip');

$Painel
    ->bloco('Campos', function () use ($Painel) {
        $Painel
            ->campo('empresa', 'Empresa')
            ->campo('codigo', 'Código')
            ->campo('data', 'Data de validação');
    });

return $Painel;
