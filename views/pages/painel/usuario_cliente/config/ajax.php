<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('grupo', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_cliente_add')
            ->scope('usuario_grupo:listar')
            ->metodo('get')
            ->rota('/usuario-grupo/select');
    })
    ->grupo('analytics', function () use ($Painel) {
        $Painel
            ->request(['usuario', 'de', 'ate', 'pagina', 'quantidade'])
            ->permissao('usuario_cliente_index')
            ->scope('relatorio_analytics:listar')
            ->metodo('get')
            ->rota('/relatorio/analytics');
    });
