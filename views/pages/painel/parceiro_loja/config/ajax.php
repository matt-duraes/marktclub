<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('auditoria', function () use ($Painel) {
        $Painel
            ->request(['parceiro', 'auditoria', 'mensagem'])
            ->permissao('parceiro_loja_editar')
            ->metodo('post')
            ->rota('/parceiro-loja/auditoria');
    })
    ->grupo('cancelar-loja', function () use ($Painel) {
        $Painel
            ->request(['cancelar_motivo', 'status'])
            ->permissao('parceiro_loja_status')
            ->metodo('put')
            ->rota('/parceiro-loja/{id}');
    });
