<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('equipe', function () use ($Painel) {
        $Painel
            ->request(['equipe'])
            ->permissao('parceiro_equipe_visualizar')
            ->metodo('put')
            ->rota('/parceiro-loja/{id}');
    });
