<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('auditoria', function () use ($Painel) {
        $Painel
            ->request(['parceiro', 'auditoria', 'mensagem'])
            ->permissao('parceiro_loja_editar')
            ->metodo('post')
            ->rota('/parceiro-loja/auditoria');
    });
