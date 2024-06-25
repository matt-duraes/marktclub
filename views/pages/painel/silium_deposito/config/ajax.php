<?php

use PainelConfig\Ajax;

$Painel = new Ajax();

$Painel
    ->grupo('saques', function () use ($Painel) {
        $Painel
            ->request(['saque'])
            ->permissao('silium_saque_index')
            ->metodo('get')
            ->rota('/silium-saque/select');
    });

return $Painel;
