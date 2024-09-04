<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('lista', function () use ($Painel) {
        $Painel
            ->request(['pagina'])
            ->permissao('view_pagina_index')
            ->metodo('get')
            ->rota('/view-lista');
    });
