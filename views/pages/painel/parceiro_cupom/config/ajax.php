<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('status-atualizar', function () use ($Painel) {
        $Painel
            ->request(['id', 'status'])
            ->permissao('parceiro_cupom_status')
            ->metodo('put')
            ->rota('/parceiro-cupom/{id}');
    });
