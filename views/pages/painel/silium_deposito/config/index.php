<?php

use PainelConfig\Index;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\Ordem;
use App\Classes\SiliumDeposito\TipoResgate;

$Painel = new Index('silium_deposito', new Ordem());

$TipoResgate = new TipoResgate();
$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('tipo_resgate', 'Tipo de Resgate', Index::TIPO_PEQUENO)
    ->campo('valor', 'Valor', Index::TIPO_PEQUENO, 'dinheiro')
    ->campo('data_deposito', 'Data de Depósito', Index::TIPO_PEQUENO, Index::FORMATAR_DATA)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo_resgate', $TipoResgate);

return $Painel;
