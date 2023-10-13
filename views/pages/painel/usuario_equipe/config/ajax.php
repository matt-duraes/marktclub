<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('subempresa', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_equipe_add')
            ->metodo('get')
            ->rota('/comercial-subempresa/select');
    });
