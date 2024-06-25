<?php

use PainelConfig\Index;
use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\OrdemDeposito;

$Painel = new Index('silium_deposito', new OrdemDeposito());

$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('saque->pontuacao', 'Pontuação', Index::TIPO_PEQUENO)
    ->campo('valor', 'Valor', Index::TIPO_PEQUENO, 'dinheiro')
    ->campo('data_deposito', 'Data de Depósito', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->status('status', 'Status', new StatusDeposito());

return $Painel;
