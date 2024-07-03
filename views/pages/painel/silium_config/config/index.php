<?php

use PainelConfig\Index;

$Painel = new Index('silium_config');

$Painel
    ->campo('pontuacao_minima_resgate->dinheiro', 'Mínimo Resgate Dinheiro', Index::TIPO_PEQUENO)
    ->campo('pontuacao_minima_resgate->mensalidade', 'Mínimo Resgate Mensalidade', Index::TIPO_PEQUENO)
    ->campo('validade_pontuacao', 'Prazo de Validade (Meses)', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->dataAtualizacao();

return $Painel;
