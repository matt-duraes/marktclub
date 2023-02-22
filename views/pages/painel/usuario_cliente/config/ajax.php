<?php

$Painel = new PainelConfig\Ajax;

return $Painel
    ->grupo('grupo', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_cliente_add')
            ->scope('usuario_grupo:listar')
            ->metodo('get')
            ->rota('/usuario-grupo/select');
    });
