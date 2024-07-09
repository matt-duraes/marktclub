<?php

use Modules\Botao;
use PainelConfig\Index;

$Painel = new Index('silium_config');

$Painel
    ->campo('empresa->nome', 'Empresa', Index::TIPO_NORMAL)
    ->campo('pontuacao_minima_resgate->dinheiro', 'Mínimo Resgate Dinheiro', Index::TIPO_PEQUENO)
    ->campo('pontuacao_minima_resgate->mensalidade', 'Mínimo Resgate Mensalidade', Index::TIPO_PEQUENO)
    ->campo('validade_pontuacao', 'Prazo de Validade (Meses)', Index::TIPO_PEQUENO)
    ->campo('desconto', 'Tem Resgate por Mensalidade', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->dataAtualizacao();

$Painel->replace('desconto', ['1' => 'Sim', '2' => 'Não']);

return $Painel;
