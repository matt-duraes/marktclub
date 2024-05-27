<?php

use App\Classes\Silium\OrdemComissao;
use App\Classes\Silium\StatusComissao;

$Painel = new PainelConfig\Index('silium_comissao', new OrdemComissao());

$Painel
    ->campo('usuario->nome', 'Usuário', 'normal')
    ->campo('empresa->titulo', 'Empresa', 'normal')
    ->campo('parceiro', 'Parceiro/Loja', 'normal')
    ->campo('valor_compra', 'Valor da Compra', 'pequeno')
    ->campo('comissao_usuario', 'Comissão', 'pequeno')
    ->campo('pontuacao', 'Pontuação', 'pequeno')
    ->campo('data_compra', 'Data de Compra', 'pequeno', 'data')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new StatusComissao());

return $Painel;
