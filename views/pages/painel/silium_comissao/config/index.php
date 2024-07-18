<?php

use PainelConfig\Index;
use App\Classes\SiliumComissao\Ordem;
use App\Classes\SiliumComissao\Status;

$Painel = new Index('silium_comissao', new Ordem());

$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('parceiro', 'Parceiro/Loja', Index::TIPO_NORMAL)
    ->campo('valor_compra', 'Valor da Compra', Index::TIPO_PEQUENO, 'dinheiro')
    ->campo('comissao_usuario', 'Comissão', Index::TIPO_PEQUENO, 'dinheiro')
    ->campo('pontuacao', 'Pontuação', Index::TIPO_PEQUENO)
    ->campo('data_compra', 'Data de Compra', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->status('status', 'Status', new Status());

return $Painel;
