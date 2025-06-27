<?php

use PainelConfig\Download;

$Painel = new Download('silium_comissao');

$Painel
    ->bloco('Dados da Compra', function () use ($Painel) {
        $Painel
            ->campo('empresa', 'Empresa')
            ->campo('parceiro', 'Loja')
            ->campo('cliente', 'Usuário')
            ->campo('valor_compra', 'Valor da Compra')
            ->campo('comissao_usuario', 'Valor da Comissão')
            ->campo('pontuacao', 'Pontuação')
            ->campo('data_compra', 'Data da Compra');
    })
    ->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_atualizacao', 'Data de atualização')
            ->campo('status', 'Status');
    });

return $Painel;
