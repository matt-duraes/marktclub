<?php

$Painel = new PainelConfig\Download('solicitacao_voucher');

return $Painel
    ->bloco('Voucher', function () use ($Painel) {
        $Painel
            ->campo('codigo', 'Código')
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_validacao', 'Data de validação')
            ->campo('data_vencimento', 'Data de vencimento')
            ->campo('status', 'Status');
    })
    ->bloco('Outros dados', function () use ($Painel) {
        $Painel
            ->campo('empresa', 'Empresa')
            ->campo('parceiro', 'Parceiro');
    })
    ->bloco('Usuário', function () use ($Painel) {
        $Painel
            ->campo('usuario_nome', 'Nome');
    });

return $Painel;
