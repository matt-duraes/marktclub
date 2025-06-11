<?php

use PainelConfig\Download;

$Painel = new Download('enquete_mercado');

$Painel
    ->bloco('Dados da Enquete', function () use ($Painel) {
        $Painel
            ->campo('fidelidade', 'Pergunta da Fidelidade')
            ->campo('produtos', 'Pergunta do Produto')
            ->campo('gasto', 'Pergunta do Gasto')
            ->campo('importancia', 'Pergunta da Importancia')
            ->campo('cashback', 'Pergunta do Cashback')
            ->campo('frequencia', 'Pergunta da Frequencia')
            ->campo('resgate', 'Pergunta do Resgate')
            ->campo('desconto', 'Pergunta do Desconto')
            ->campo('experiencia', 'Pergunta da Experiencia')
            ->campo('indicaria', 'Pergunta da Indicação');
    })
    ->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_atualizacao', 'Data de atualização');
    });

return $Painel;
