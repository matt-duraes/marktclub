<?php

use PainelConfig\Index;
use App\Classes\Silium\OrdemComissao;
use App\Classes\Silium\StatusComissao;

$Painel = new Index('silium_comissao', new OrdemComissao());

$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('parceiro', 'Parceiro/Loja', Index::TIPO_NORMAL)
    ->campo('valor_compra', 'Valor da Compra', Index::TIPO_PEQUENO)
    ->campo('comissao_usuario', 'Comissão', Index::TIPO_PEQUENO)
    ->campo('pontuacao', 'Pontuação', Index::TIPO_PEQUENO)
    ->campo('data_compra', 'Data de Compra', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->status('status', 'Status', new StatusComissao());

return $Painel;
