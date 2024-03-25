<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('auditoria', function () use ($Painel) {
        $Painel
            ->request(['parceiro', 'resultado', 'mensagem'])
            ->permissao('parceiro_loja:salvar')
            ->metodo('post')
            ->rota('/parceiro-loja/auditoria');
    });
