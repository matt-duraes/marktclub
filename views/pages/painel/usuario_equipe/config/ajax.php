<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('subempresa', function () use ($Painel) {
        $Painel
            ->request(['empresa', 'titulo'])
            ->permissao('usuario_equipe_add')
            ->scope('comercial_subempresa:select')
            ->metodo('get')
            ->rota('/comercial-subempresa/select');
    });
