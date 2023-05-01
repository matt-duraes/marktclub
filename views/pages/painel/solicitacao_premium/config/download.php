<?php

$Painel = new PainelConfig\Download('solicitacao_voucher');

return $Painel
    ->bloco('Voucher', function () use ($Painel) {
        $Painel
            ->campo('parceiro', 'Parceiro')
            ->campo('codigo', 'Código')
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_validacao', 'Data de validação')
            ->campo('data_vencimento', 'Data de vencimento')
            ->campo('status', 'Status');
    });

return $Painel;
