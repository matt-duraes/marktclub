<?php

use App\Classes\Silium\StatusDeposito;
use App\Classes\Silium\OrdemDeposito;

$Painel = new PainelConfig\Index('silium_saque', new OrdemDeposito());

$Painel
    ->campo('usuario->nome', 'Usuário', 'normal')
    ->campo('empresa->titulo', 'Empresa', 'normal')
    ->campo('pontuacao', 'Pontuação', 'pequeno')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new StatusDeposito());

return $Painel;
