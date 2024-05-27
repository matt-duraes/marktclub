<?php

use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\OrdemDeposito;

$Painel = new PainelConfig\Index('silium_deposito', new OrdemDeposito());

$Painel
    ->campo('usuario->nome', 'Usuário', 'normal')
    ->campo('empresa->titulo', 'Empresa', 'normal')
    ->campo('pontuacao', 'Pontuação', 'pequeno')
    ->campo('valor', 'Valor', 'pequeno')
    ->campo('data_deposito', 'Data de Depósito', 'pequeno', 'data')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new StatusDeposito());

return $Painel;
