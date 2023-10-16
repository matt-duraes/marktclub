<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('grupo', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_cliente_add')
            ->metodo('get')
            ->rota('/usuario-grupo/select');
    })
    ->grupo('subempresa', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_cliente_add')
            ->metodo('get')
            ->rota('/comercial-subempresa/select');
    })
    ->grupo('analytics', function () use ($Painel) {
        $Painel
            ->request(['usuario', 'de', 'ate', 'pagina', 'quantidade'])
            ->permissao('usuario_cliente_index')
            ->metodo('get')
            ->rota('/relatorio/analytics');
    });
