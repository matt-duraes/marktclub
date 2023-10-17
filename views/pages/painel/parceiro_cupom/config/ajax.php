<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('status-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'status'])
            ->permissao('parceiro_cupom_status')
            //->scope('parceiro_cupom:atualizar')
            ->metodo('put')
            ->rota('/parceiro-cupom/{id}');
    });
